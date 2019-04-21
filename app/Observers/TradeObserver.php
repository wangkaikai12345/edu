<?php

namespace App\Observers;

use App\Enums\CouponType;
use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Enums\StudentStatus;
use App\Enums\StudentType;
use App\Models\ClassroomMember;
use App\Models\Order;
use App\Models\Trade;
use App\Traits\JoinTrait;

class TradeObserver
{
    use JoinTrait;

    /**
     * 监听交易记录创建事件
     *
     * @param Trade $trade
     */
    public function created(Trade $trade)
    {
        // 现金订单（虚拟币订单同步更新数据、现金订单异步更新数据）
        if ($trade->currency !== Currency::COIN) {
            $product = $trade->order->product;
            $user = $trade->user;
            \DB::transaction(function () use ($trade, $product, $user) {
                // 更新订单状态
                $order = $trade->order;
                $order->status = OrderStatus::SUCCESS;
                $order->paid_amount = $trade->paid_amount;
                $order->paid_at = $trade->paid_at;
                $order->payment = $trade->payment;
                $order->finished_at = $trade->paid_at->addDays(Order::$refundDays);// 需要计算
                $order->refund_deadline = $trade->paid_at->addDays(Order::$refundDays);// 需要计算
                $order->save();

                // 充值
                if ($order->product_type === ProductType::RECHARGING) {
                    $user->coin = $user->coin + ($trade->order->pay_amount * config('app.recharge_proportion'));
                    $user->recharge = $user->recharge + ($trade->order->pay_amount * config('app.recharge_proportion'));
                    $user->save();
                }
                if ($order->product_type === ProductType::PLAN) {
                    // 将用户移入版本之中
                    $this->joinPlan($order);
                } else if ($order->product_type === ProductType::CLASSROOM) {
                    // 将用户移入班级之中
                    $this->joinClassroom($order);
                }
            });
        }
    }
}
