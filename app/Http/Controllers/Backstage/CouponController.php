<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\CouponStatus;
use App\Enums\CouponType;
use App\Exports\ConversationExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use App\Models\Course;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Ramsey\Uuid\Uuid;

class CouponController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $coupons = Coupon::filtered(array_filter($request->all()))->sorted()
            ->with(['user', 'consumer', 'product'])
            ->paginate(self::perPage());

        // 课程状态
        $status = ['draft' => '未发布', 'closed' => '已关闭', 'published' => '已发布'];

        // 类型
        $types = ['discount' => '折扣券', 'voucher' => '代金券', 'audition' => '试听券'];

        // 使用状态
        $couponStatus = ['unused' => '未使用', 'used' => '已使用'];

        return view('admin.coupon.index', compact('coupons', 'status', 'types', 'couponStatus'));
    }


    /**
     * 保存
     *
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
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

            return new Response([], 200, []);
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

        return new Response([], 200, []);
    }

    /**
     * 删除
     *
     * @return Response
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
     * 批量撤销
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function batch()
    {
        self::orderBy();

        $coupons = Coupon::groupBy('batch')
            ->whereNotNull('batch')
            ->sorted()
            ->paginate(self::perPage());

        return view('admin.coupon.coupon_batch', compact('coupons'));
    }

    /**
     * 优惠券
     *
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export()
    {
        return \Excel::download(new ConversationExport, '优惠券' . now()->format('Y-m-d') . '.xlsx');
    }


    public function show(Coupon $coupon)
    {
        return $this->response->item($coupon, new CouponTransformer());
    }


    /**
     * 获取课程信息
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function courses(Request $request)
    {
        // 获取学科书籍
        $courses = Course::filtered(array_filter($request->all()))
            ->sorted()
            ->withCount(['plans'])
            ->with(['user', 'default_plan'])
            ->paginate(self::perPage());

        // 课程状态
        $status = ['draft' => '未发布', 'closed' => '已关闭', 'published' => '已发布'];

        return view('admin.coupon.course', compact('courses', 'status'));
    }
}
