<?php

namespace App\Observers;

use App\Enums\CouponStatus;
use App\Models\Order;

class OrderObserver
{
    /**
     * 监听订单创建
     *
     * @param Order $order
     */
    public function created(Order $order)
    {
        if ($coupon = $order->coupon) {
            $coupon->status = CouponStatus::USED;
            $coupon->consumer_id = $order->user_id;
            $coupon->consumed_at = now();
            $coupon->save();
        }
    }
}
