<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CouponStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Http\Transformers\CouponTransformer;
use App\Models\Coupon;
use Ramsey\Uuid\Uuid;

/**
 * @SWG\Path(
 *   path="/admin/coupons/{coupon_code}",
 *
 * )
 */

class CouponController extends Controller
{
    /**
     * @SWG\Tag(name="admin/coupon",description="优惠券")
     */

    /**
     * @SWG\GET(
     *  path="/admin/coupons",
     *  tags={"admin/coupon"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-code"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-type"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-consumer:username"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-consumer_id"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-expired_at"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-consumed_at"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-batch"),
     *  @SWG\Parameter(ref="#/parameters/CouponQuery-product_type"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/Coupon-include"),
     *  @SWG\Parameter(ref="#/parameters/Coupon-sort"),
     *  @SWG\Response(response=200,ref="#/responses/CouponPagination"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index()
    {
        $data = Coupon::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new CouponTransformer());
    }

    /**
     * @SWG\GET(
     *  path="/admin/coupons/{code}",
     *  tags={"admin/coupon"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="code",type="string",in="path",required=true,description="优惠码"),
     *  @SWG\Parameter(ref="#/parameters/Coupon-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Coupon"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function show(Coupon $coupon)
    {
        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/coupons",
     *  tags={"admin/coupon"},
     *  summary="单个添加/批量添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/CouponForm-type"),
     *  @SWG\Parameter(ref="#/parameters/CouponForm-value"),
     *  @SWG\Parameter(ref="#/parameters/CouponForm-expired_at"),
     *  @SWG\Parameter(ref="#/parameters/CouponForm-consumer_id"),
     *  @SWG\Parameter(ref="#/parameters/CouponForm-consumer_id"),
     *  @SWG\Parameter(ref="#/parameters/CouponForm-product_type"),
     *  @SWG\Parameter(ref="#/parameters/CouponForm-product_id"),
     *  @SWG\Parameter(name="number",in="formData",type="integer",description="生成优惠券个数",maximum=1000,default=1),
     *  @SWG\Response(response=201,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function store(CouponRequest $request)
    {
        $productType = $request->product_type;
        $productId = $request->product_id;
        if ($productType && $productId) {
            $model = 'App\\Models\\' . ucfirst($request->product_type);
            $productId = resolve($model)->findOrFail($request->product_id)->id;
        }

        // 赋值
        $batchCode = Uuid::uuid4()->toString();
        $userId = auth()->id();
        $createdAt = now();

        // 单个
        if (!$request->number || $request->number == 1) {
            $coupon = new Coupon($request->all());
            $coupon->code = $batchCode;
            $coupon->batch = $batchCode;
            $coupon->user_id = $userId;
            $coupon->consumer_id = $request->consumer_id;
            $coupon->product_id = $productId;
            $coupon->product_type = $productType;
            $coupon->save();

            return $this->response->item($coupon, new CouponTransformer());
        }

        $coupons = [];
        for ($i = 0; $i < $request->number; $i++) {
            $coupon = $request->only(resolve(Coupon::class)->getFillable());
            $coupon['code'] = Uuid::uuid4()->toString();
            $coupon['batch'] = $batchCode;
            $coupon['user_id'] = $userId;
            $coupon['product_type'] = $productType;
            $coupon['created_at'] = $createdAt;
            $coupon['updated_At'] = $createdAt;
            array_push($coupons, $coupon);
        }

        Coupon::insert($coupons);

        return $this->response->created();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/coupons",
     *  tags={"admin/coupon"},
     *  summary="单个删除/批量删除",
     *  description="id 与 batch 二选一即可，仅未使用的优惠券允许删除",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="code",in="formData",type="string",description="优惠码"),
     *  @SWG\Parameter(name="batch",in="formData",type="string",description="批次码"),
     *  @SWG\Response(response=204,description="ok"),
     * )
     */
    public function destroy()
    {
        if ($code = request('code')) {
            Coupon::where('code', $code)->where('status', '=', CouponStatus::UNUSED)->delete();
        }

        if ($batch = request('batch')) {
            Coupon::where('batch', $batch)->where('status', '=', CouponStatus::UNUSED)->delete();
        }

        return $this->response->noContent();
    }

    /**
     * @SWG\Get(
     *  path="/admin/coupon-batches",
     *  tags={"admin/coupon"},
     *  summary="批次号码",
     *  description="此处用于展示批次号码列表",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/Coupon-include"),
     *  @SWG\Parameter(ref="#/parameters/Coupon-sort"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/CouponResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function batch()
    {
        self::orderBy();

        $data = Coupon::groupBy('batch')
            ->whereNotNull('batch')
            ->sorted()
            ->paginate(self::perPage());

        return $this->response->paginator($data, new CouponTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/export-coupons",
     *  tags={"admin/coupon"},
     *  summary="导出",
     *  description="JSON 数据",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/Coupon-include"),
     *  @SWG\Parameter(ref="#/parameters/Coupon-sort"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/CouponResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function export()
    {
        $coupons = Coupon::filtered()->sorted()->get();

        return $this->response->collection($coupons, new CouponTransformer());
    }
}
