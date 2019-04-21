<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Refund;

class RefundObserver
{
    /**
     * 监听退款创建、更新事件
     *
     * @param Refund $refund
     */
    public function saved(Refund $refund)
    {
        $order = $refund->order;
        $trade = $refund->trade;
        switch ($refund->status) {
            // 申请退款新建
            case OrderStatus::CREATED:
                \DB::transaction(function () use ($order, $trade) {
                    // 更新订单状态
                    $order->status = OrderStatus::REFUNDING;
                    $order->save();
                    // 更新交易记录状态
                    $trade->status = OrderStatus::REFUNDING;
                    $trade->save();
                });
                break;
            // 申请退款审核拒绝
            case OrderStatus::REFUND_DISAGREE:
                \DB::transaction(function () use ($order, $trade) {
                    // 更新订单状态
                    $order->status = OrderStatus::REFUND_DISAGREE;
                    $order->save();
                    // 更新交易记录状态
                    $trade->status = OrderStatus::REFUND_DISAGREE;
                    $trade->save();
                });
                break;
            // 申请退款关闭，则恢复之前的状态
            case OrderStatus::CLOSED:
                \DB::transaction(function () use ($order, $trade) {
                    // 更新订单状态
                    $order->status = OrderStatus::SUCCESS;
                    $order->save();
                    // 更新交易记录状态
                    $trade->status = OrderStatus::SUCCESS;
                    $trade->save();
                });
                break;
            default:
        }
    }
}
