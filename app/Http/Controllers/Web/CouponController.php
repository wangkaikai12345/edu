<?php

namespace App\Http\Controllers\Web;

use App\Enums\CouponStatus;
use App\Http\Controllers\Controller;
use App\Http\Transformers\CouponTransformer;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * @SWG\Tag(name="web/coupon",description="前台优惠券")
     */

    /**
     * @SWG\Get(
     *  path="/coupons",
     *  tags={"web/coupon"},
     *  summary="列表",
     *  description="我的优惠券",
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
     *  },
     * )
     */
    public function index()
    {
        $coupons = Coupon::filtered()->sorted()->where('consumer_id', auth()->id())->paginate(self::perPage());

        return $this->response->paginator($coupons, new CouponTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/coupons",
     *  tags={"web/coupon"},
     *  summary="激活",
     *  description="激活我的优惠券",
     *  @SWG\Parameter(in="formData",name="coupon_code",type="string",required=true,description="优惠码"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Coupon"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request)
    {
        $coupon = Coupon::findOrFail($request->coupon_code);

        if ($coupon->expired_at->lt(now())) {
            $this->response->errorBadRequest(__('Coupon code is out of date.'));
        }

        if ($coupon->status === CouponStatus::ACTIVATED || $coupon->consumer_id) {
            $this->response->errorBadRequest(__('Coupon code has been activated.'));
        }

        $coupon->consumer_id = auth()->id();
        $coupon->status = CouponStatus::ACTIVATED;
        $coupon->save();

        return $this->response->item($coupon, new CouponTransformer())->setStatusCode(201);
    }
}
