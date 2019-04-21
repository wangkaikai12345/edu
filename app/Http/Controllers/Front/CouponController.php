<?php

namespace App\Http\Controllers\Front;

use App\Enums\CouponStatus;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Recharging;
use App\Rules\CustomEnumRule;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * 优惠券使用
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function store(Request $request)
    {
        // 数据验证
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
               return ajax('400', '商品不存在');
        }

        // 状态
        if ($coupon->status === CouponStatus::USED) return ajax('400', '优惠券已经被使用');
        // 有效期
        if ($coupon->expired_at->lt(now())) return ajax('400', '优惠券已经过期了');
        // 指定使用人
        if ($coupon->consumer_id && $coupon->consumer_id !== auth('web')->id()) return ajax('400', '优惠码已被激活，无法使用');
        // 适用商品
        if ($coupon->product_type && ($coupon->product_type !== $request->product_type || $coupon->product_id !== (int)$request->product_id)) {
            return ajax('400', '优惠码不适用于本商品');
        }

        $payAmount = $coupon->calculatePrice($product->price);

        return ajax('200', '有效优惠券', [
            'price' => $product->price,
            'pay_amount' => $payAmount,
        ]);

    }
}
