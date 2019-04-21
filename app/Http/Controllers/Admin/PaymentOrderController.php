<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Payment;
use App\Traits\PayInstance;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentOrderController extends Controller
{
    use PayInstance;

    /**
     * @SWG\Get(
     *  path="/admin/payment-orders",
     *  tags={"admin/order"},
     *  summary="第三方支付平台的对账单下载",
     *  description="自动会根据订单的 payment 支付方式自动获取第三支付平台的对账单",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="payment",type="string",enum={"alipay","wechat"},in="formData"),
     *  @SWG\Parameter(name="bill_type",type="string",enum={"trade","signcustomer"},in="formData",description="trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；"),
     *  @SWG\Parameter(name="bill_date",type="string",in="formData",description="日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。"),
     *  @SWG\Response(response=200,description="账单下载地址链接，获取连接后30秒后未下载，链接地址失效。",@SWG\Definition(
     *     @SWG\Property(property="bill_download_url",example="http://dwbillcenter.alipay.com/downloadBillFile.resource?bizType=X&userId=X&fileType=X&bizDates=X&downloadFileName=X&fileId=X",description="账单下载地址"),
     *  )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
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

    /**
     * @SWG\GET(
     *  path="/admin/payment-orders/{order_id}",
     *  tags={"admin/order"},
     *  summary="第三方支付平台的订单详情查询",
     *  description="自动会根据订单的 payment 支付方式自动获取第三支付平台的账单信息",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="order_id",type="integer",in="path"),
     *  @SWG\Parameter(name="is_refund",type="boolean",default=false,in="query",description="false/true 普通订单/退款订单"),
     *  @SWG\Response(response=200,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
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

    /**
     * @SWG\PATCH(
     *  path="/admin/payment-orders/{order_id}",
     *  tags={"admin/order"},
     *  summary="第三方支付平台的订单取消",
     *  description="自动会根据订单的 payment 支付方式自动获取第三支付平台的订单",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="order_id",type="integer",in="path"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
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
