<?php

namespace App\Http\Controllers\Web;

use App\Enums\CouponStatus;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Recharging;
use App\Rules\CustomEnumRule;
use Illuminate\Http\Request;

class CalculateController extends Controller
{
    /**
     * @SWG\Post(
     *  path="/calculate-price",
     *  tags={"web/coupon"},
     *  summary="计算价格",
     *  description="在填写完优惠券时，调用本接口，自动根据优惠码计算优惠额度",
     *  @SWG\Parameter(name="coupon_code",type="string",in="formData",required=true,description="优惠码"),
     *  @SWG\Parameter(name="product_type",type="string",in="formData",enum={"plan","recharging","classroom"},required=true,description="商品类型行：课程版本/充值订单"),
     *  @SWG\Parameter(name="product_id",type="integer",in="formData",required=true,description="商品ID"),
     *  @SWG\Response(response=200,description="单位：分；数据结构：{'pay_amount':1}"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'coupon_code' => 'required|exists:coupons,code',
            'product_type' => ['required', new CustomEnumRule(ProductType::class)],
            'product_id' => 'required|integer'
        ]);

        $coupon = Coupon::find($request->coupon_code);

        switch ($request->product_type) {
            case ProductType::PLAN:
                $product = Plan::findOrFail($request->product_id);
                break;
            case ProductType::CLASSROOM:
                $product = Classroom::findOrFail($request->product_id);
                break;
            case ProductType::RECHARGING:
                $product = Recharging::findOrFail($request->product_id);
                break;
            default:
                $this->response->errorBadRequest(__('Product not found.'));
        }

        // 状态
        if ($coupon->status === CouponStatus::USED) {
            $this->response->errorBadRequest(__('Coupon code has been used.'));
        }
        // 有效期
        if ($coupon->expired_at->lt(now())) {
            $this->response->errorBadRequest(__('Coupon code is out of date.'));
        }
        // 指定使用人
        if ($coupon->consumer_id && $coupon->consumer_id !== auth()->id()) {
            $this->response->errorBadRequest(__('Coupon code has been activated.'));
        }
        // 适用商品
        if ($coupon->product_type && ($coupon->product_type !== $request->product_type || $coupon->product_id !== (int)$request->product_id)) {
            $this->response->errorBadRequest(__('Coupon code does not apply.'));
        }

        $payAmount = $coupon->calculatePrice($product->price);

        return $this->response->array([
            'price' => $product->price,
            'pay_amount' => $payAmount,
        ]);
    }
}
