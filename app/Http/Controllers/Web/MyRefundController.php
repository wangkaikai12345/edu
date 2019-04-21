<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RefundRequest;
use App\Http\Transformers\RefundTransformer;
use App\Models\Order;
use App\Models\Refund;
use DB;

class MyRefundController extends Controller
{
    /**
     * @SWG\Tag(name="web/refund",description="退款")
     */

    /**
     * @SWG\Get(
     *  path="/my-refunds",
     *  tags={"web/refund"},
     *  summary="列表",
     *  description="默认顺序创建时间倒序",
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-order_id"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-payment"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-payment_sn"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-currency"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-applied_amount"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-refunded_amount"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-handled_at"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-handler_id"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-handler:username"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/Refund-sort"),
     *  @SWG\Parameter(ref="#/parameters/Refund-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/RefundPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $refunds = Refund::where('user_id', auth()->id())->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($refunds, new RefundTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/my-refunds/{refund_id}",
     *  tags={"web/refund"},
     *  summary="详情",
     *  description="",
     *  @SWG\Parameter(name="refund_id",type="integer",in="path",required=true,description="退款申请ID"),
     *  @SWG\Parameter(ref="#/parameters/Refund-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Refund"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show($refund)
    {
        $refund = Refund::where('user_id', auth()->id())->findOrFail($refund);

        return $this->response->item($refund, new RefundTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/my-orders/{order_id}/refunds",
     *  tags={"web/refund"},
     *  summary="发起退款申请",
     *  @SWG\Parameter(name="order_id",in="path",type="integer",required=true,description="订单ID"),
     *  @SWG\Parameter(ref="#/parameters/RefundForm-applied_amount"),
     *  @SWG\Parameter(ref="#/parameters/RefundForm-reason"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Refund"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store($order, RefundRequest $request)
    {
        if (Refund::where('user_id', auth()->id())->where('order_id', $order)->where('status', OrderStatus::CREATED)->exists()) {
            $this->response->errorBadRequest(__('Repeat application.'));
        }

        $order = Order::where('user_id', auth()->id())->findOrFail($order);
        if (!$order->paid_amount) {
            $this->response->errorBadRequest(__('No refund for free orders.'));
        }

        if ($order->status !== OrderStatus::SUCCESS) {
            $this->response->errorBadRequest(__('Abnormal order.'));
        }

        // 是否已超过退款截止日期
        if ($order->refund_deadline->lt(now())) {
            $this->response->errorBadRequest(__('Beyond refund period.'));
        }

        // 申请金额是否大于已支付金额
        if ($request->applied_amount > $order->paid_amount) {
            $this->response->errorBadRequest(__('Applied amount can\'t exceed paid amount.'));
        }

        $trade = $order->trade;
        $refund = DB::transaction(function () use ($order, $trade) {
            $refund = new Refund();
            $refund->title = $order->title;
            $refund->order_id = $order->id;
            $refund->user_id = $order->user_id;
            $refund->payment = $trade->payment;
            $refund->payment_sn = $trade->payment_sn;
            $refund->status = OrderStatus::CREATED;
            $refund->reason = request('reason');
            $refund->currency = $trade->currency;
            $refund->applied_amount = request('applied_amount');
            $refund->save();
            return $refund;
        });

        return $this->response->item($refund, new RefundTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/my-refunds/{refund_id}",
     *  tags={"web/refund"},
     *  summary="关闭/撤销",
     *  @SWG\Parameter(name="refund_id",in="path",required=true,type="integer",description="退款ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update($refund)
    {
        $refund = Refund::where('user_id', auth()->id())->findOrFail($refund);

        // 异常订单
        if ($refund->status !== OrderStatus::CREATED) {
            $this->response->errorForbidden(__('Abnormal order.'));
        }

        $refund->status = OrderStatus::CLOSED;
        $refund->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/my-refunds/{refund_id}",
     *  tags={"web/refund"},
     *  summary="删除",
     *  @SWG\Parameter(name="refund_id",in="path",required=true,type="integer",description="退款ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy($refund)
    {
        $refund = Refund::where('user_id', auth()->id())->findOrFail($refund);

        // 当订单已退款、已关闭、已完结、被拒绝是，允许删除退款订单
        if (in_array($refund->status, [OrderStatus::REFUNDED, OrderStatus::CLOSED, OrderStatus::FINISHED, OrderStatus::REFUND_DISAGREE])) {
            $refund->delete();
        } else {
            $this->response->errorBadRequest(__('Only refunded,closed,finished,refund_disagree status can be deleted.'));
        }
        return $this->response->noContent();
    }
}
