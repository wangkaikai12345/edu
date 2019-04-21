<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\Payment;
use App\Traits\PayInstance;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentOrderController extends Controller
{
    use PayInstance;

    public function bill(Request $request)
    {
        $this->validate($request, [
            'payment' => 'required|in:alipay,wechat',
            'bill_type' => 'required|in:trade,signcustomer',
            'bill_date' => 'required|date',
        ]);

        // TODO 暂不支持支持微信对账单，需要自己写
        if ($request->payment === Payment::WECHAT) {
            $this->response->errorBadRequest(__('No supported.'));
        }

        $params = $request->only(['bill_date', 'bill_type']);

        $pay = $this->payInstance($request->payment);

        $response = $pay->download($params);

        return $this->response->array($response->toArray());
    }

    public function search(Request $request, Order $order)
    {
        if (!$order->payment) {
            abort(400, __('Unpaid order.'));
        }

        if ($order->payment === Payment::COIN) {
            abort(400, __('Coin order has not third-party trade.'));
        }

        $params = ['out_trade_no' => $order->trade_uuid];

        if ($request->is_refund) {
            $order->payment === Payment::WECHAT
                ? $params['out_refund_no'] = $order->refund->payment_sn
                : $params['out_request_no'] = $order->refund->payment_sn;
        }

        $pay = $this->payInstance($order->payment);

        $response = $pay->find($params);

        return $this->response->array($response->toArray());
    }

    public function cancel(Order $order)
    {
        if (!$order->payment) {
            abort(400, __('Unpaid order.'));
        }

        if ($order->payment === Payment::COIN) {
            abort(400, __('Coin order has not third-party trade.'));
        }

        $params = ['out_trade_no' => $order->trade_uuid];

        $pay = $this->payInstance($order->payment);

        $response = $pay->close($params);

        return $this->response->array($response->toArray());
    }
}
