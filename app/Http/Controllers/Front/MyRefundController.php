<?php

namespace App\Http\Controllers\Front;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\RefundRequest;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;

class MyRefundController extends Controller
{
    /**
     * 我的退款列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function index(Request $request)
    {
        $request->flash();

        // 组装搜索条件
        $search = $request->title ? [ 'title' => '%'.$request->title.'%' ] : [];

        // 查询用户的订单
        $refunds = auth('web')->user()
            ->refunds()
            ->filtered($search)
            ->sorted()
            ->with('order')
            ->paginate(config('theme.plan_detail'))
            ->appends($request->only(['title']));

        return frontend_view('personal.refund', compact('refunds'));
    }

    /**
     * 查看退款详情
     *
     * @param $refund
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function show($refund)
    {
        $refund = auth('web')->user()->refunds()->findOrFail($refund);

        return frontend_view('personal.refund-modal', compact('refund'));
    }

    /**
     * 退款申请
     *
     * @param $order
     * @param RefundRequest $request
     * @return $this
     * @author 王凯
     */
    public function store($order, RefundRequest $request)
    {
        if (Refund::where('user_id', auth('web')->id())->where('order_id', $order)->where('status', OrderStatus::CREATED)->exists()) {
            return ajax('400', __('Repeat application.'));
        }

        $order = Order::where('user_id', auth('web')->id())->findOrFail($order);
        if (!$order->paid_amount) {
            return ajax('400', __('No refund for free orders.'));
        }

        if ($order->status !== OrderStatus::SUCCESS) {
            return ajax('400', __('Abnormal order.'));
        }

        // 是否已超过退款截止日期
        if ($order->refund_deadline->lt(now())) {
            return ajax('400', __('Beyond refund period.'));
        }

        // 申请金额是否大于已支付金额
        if ($request->applied_amount &&  $request->applied_amount > $order->paid_amount) {
            return ajax('400',__('Applied amount can\'t exceed paid amount.'));
        }

        $trade = $order->trade;

        $refund = new Refund();
        $refund->title = $order->title;
        $refund->order_id = $order->id;
        $refund->user_id = $order->user_id;
        $refund->payment = $trade->payment;
        $refund->payment_sn = $trade->payment_sn;
        $refund->status = OrderStatus::REFUNDING;
        $refund->reason = request('reason');
        $refund->currency = $trade->currency;
        $refund->applied_amount = $order->paid_amount;
        $refund->refunded_amount = $order->paid_amount;
        $refund->save();

        return ajax('200','申请退款成功');

    }

    /**
     * 取消退款
     *
     * @param $refund
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function update($refund)
    {
        $refund = Refund::where('user_id', auth('web')->id())->findOrFail($refund);

        // 异常订单
        if ($refund->status !== OrderStatus::REFUNDING) return back()->with('error', __('Abnormal order.'));

        $refund->status = OrderStatus::CLOSED;
        $refund->save();

        return back()->with('success', '取消退款成功');
    }

    /**
     * 删除退款
     *
     * @param $refund
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function destroy($refund)
    {
        $refund = Refund::where('user_id', auth('web')->id())->findOrFail($refund);

        // 当订单已退款、已关闭、已完结、被拒绝是，允许删除退款订单
        if (in_array($refund->status, [OrderStatus::REFUNDED, OrderStatus::CLOSED, OrderStatus::FINISHED, OrderStatus::REFUND_DISAGREE])) {
            $refund->delete();
        } else {
            return back()->with('error', __('Only refunded,closed,finished,refund_disagree status can be deleted.'));
        }

        return back()->with('success', '删除成功');
    }

}