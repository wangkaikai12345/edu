<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\OrderStatus;
use App\Enums\Payment;
use App\Enums\ProductType;
use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Models\ClassroomMember;
use App\Traits\PayInstance;
use App\Http\Requests\Admin\RefundAuditRequest;
use App\Http\Transformers\RefundTransformer;
use App\Models\PlanMember;
use App\Models\Refund;
use DB;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    use PayInstance;

    /**
     * 退款列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $refunds = Refund::filtered()
            ->sorted()
            ->with(['order', 'user', 'handler'])
            ->paginate(self::perPage());

        return view('admin.refunds.index', compact('refunds'));
    }

    /**
     * 退款审核页
     *
     * @param Refund $refund
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refundShow(Refund $refund)
    {
        $refund->load('order');
        $refund->load('user');
        $refund->load('handler');

        return view('admin.refunds.examine', compact('refund'));
    }


    /**
     * 查看详情
     *
     * @param Refund $refund
     * @return \Dingo\Api\Http\Response
     */
    public function show(Refund $refund)
    {
        $refund->load('order');
        $refund->load('user');
        $refund->load('handler');

        return view('admin.refunds.show', compact('refund'));
    }

    /**
     * 退款
     *
     * @param Refund $refund
     * @param RefundAuditRequest $request
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function audit(Refund $refund, RefundAuditRequest $request)
    {
        if (!$trade = $refund->trade) {
            $this->response->errorBadRequest(__('No trade for the refund.'));
        }

        if (!in_array($refund->status, [OrderStatus::CREATED, OrderStatus::REFUND_FAILED])) {
            $this->response->errorBadRequest(__('Repeat audit.'));
        }

        // 不同意退款
        if (!$request->agree) {
            $refund->status = OrderStatus::REFUND_DISAGREE;
            $refund->handler_id = auth()->id();
            $refund->handled_reason = $request->handled_reason;
            $refund->handled_at = now();
            $refund->save();

            return $this->response->item($refund, new RefundTransformer())->setStatusCode(201);
        }

        // 虚拟币退款
        if ($refund->payment === Payment::COIN) {
            $order = $refund->order;
            $product = $order->product;
            $trade = $refund->trade;
            DB::transaction(function () use ($refund, $order, $product, $trade) {
                // 退款到账户
                $user = $refund->user;
                $user->increment('coin', $refund->applied_amount);
                $user->save();

                // 更新退款表信息
                $refund->status = OrderStatus::REFUNDED;
                $refund->refunded_amount = $refund->applied_amount;
                $refund->handler_id = auth()->id();
                request('handled_reason') && $refund->handled_reason = request('handled_reason');
                $refund->handled_at = now();
                $refund->save();

                // 更新订单信息
                $order->status = OrderStatus::REFUNDED;
                $order->finished_at = now();// 退款完成更新为已完结时间
                $order->save();

                // 更新交易记录信息
                $trade->status = OrderStatus::REFUNDED;
                $trade->save();

                // 学员移除
                if ($order->product_type === ProductType::PLAN) {
                    PlanMember::where('user_id', $refund->user_id)->where('plan_id', $product->id)->update(['status' => StudentStatus::EXITED]);
                } else if ($order->product_type === ProductType::CLASSROOM) {
                    ClassroomMember::where('user_id', $refund->user_id)->where('classroom_id', $product->id)->update(['status' => StudentStatus::EXITED]);
                }
            });

            return $this->response->noContent();
        }

        // 现金退款
        $pay = $this->payInstance($trade->payment);
        // 为了简便，退款订单与当前订单交易号保持一致，当然这也有局限性（对一个订单无法发起多次退款）
        if ($trade->payment === Payment::ALIPAY) {
            $refundData = [
                'out_trade_no' => $trade->trade_uuid,
                'out_request_no' => $trade->trade_uuid,
                'refund_amount' => number_format($refund->applied_amount / 100, 2),// 交易金额 支付宝以元为单位,
            ];
        } else {
            $refundData = [
                'out_trade_no' => $trade->trade_uuid,
                'out_refund_no' => $trade->trade_uuid,
                'total_fee' => $trade->paid_amount,
                'refund_fee' => $refund->applied_amount, // 交易金额 微信以分为单位
            ];
        }

        // 当异常时，依然存储第三方平台返回的数据
        try {
            $result = $pay->refund($refundData);
        } catch (\Yansongda\Pay\Exceptions\GatewayException $exception) {
            $refund->payment_callback = $exception->raw;
            $refund->status = OrderStatus::REFUND_FAILED;
            $refund->save();
            abort(400, $exception->getMessage());
        } catch (\Yansongda\Pay\Exceptions\InvalidSignException $exception) {
            $refund->payment_callback = $exception->raw;
            $refund->status = OrderStatus::REFUND_FAILED;
            $refund->save();
            abort(400, $exception->getMessage());
        }

        $this->operate($refund, $result);

        return $this->response->item($refund, new RefundTransformer())->setStatusCode(201);
    }

    /**
     * 退款回调
     *
     * @param Refund $refund
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function manual(Refund $refund)
    {
        if ($refund->status === OrderStatus::REFUND_FAILED) {
            $this->response->errorBadRequest(__('Apply to refund failed orders.'));
        }

        if (!in_array($refund->payment, [Payment::WECHAT, Payment::ALIPAY])) {
            $this->response->errorBadRequest(__('Unknown payment.'));
        }

        $pay = $this->payInstance($refund->payment);

        try {
            if ($refund->payment === Payment::WECHAT) {
                $order = ['out_trade_no' => $refund->payment_sn,];
                $result = $pay->find($order, true);
            } else {
                $order = [
                    'out_trade_no' => $refund->payment_sn,
                    'out_request_no' => $refund->payment_sn,
                ];
                $result = $pay->find($order);
            }
        } catch (\Yansongda\Pay\Exceptions\GatewayException $exception) {
            abort(400, $exception->getMessage());
        } catch (\Yansongda\Pay\Exceptions\InvalidGatewayException $exception) {
            abort(400, $exception->getMessage());
        } catch (\Yansongda\Pay\Exceptions\InvalidSignException $exception) {
            abort(400, $exception->getMessage());
        } catch (\Yansongda\Pay\Exceptions\InvalidConfigException $exception) {
            abort(400, $exception->getMessage());
        }

        $this->operate($refund, $result);

        return $this->response->item($refund, new RefundTransformer())->setStatusCode(201);
    }


    /**
     * 学员移除
     *
     * @param Refund $refund
     * @param \Yansongda\Supports\Collection $result
     * @throws \Throwable
     */
    protected function operate(Refund $refund, \Yansongda\Supports\Collection $result)
    {
        // 课程订单
        $order = $refund->order;
        $trade = $refund->trade;
        $product = $order->product;
        if (in_array($order->product_type, [ProductType::PLAN, ProductType::CLASSROOM])) {
            DB::transaction(function () use ($refund, $result, $order, $trade, $product) {
                // 更新退款表信息
                $refund->status = OrderStatus::REFUNDED;
                $refund->refunded_amount = $refund->applied_amount;
                $refund->handler_id = auth()->id();
                request('handled_reason') && $refund->handled_reason = request('handled_reason');
                $refund->handled_at = now();
                $refund->payment_callback = $result->toArray();
                $refund->save();

                // 更新订单信息
                $order->status = OrderStatus::REFUNDED;
                $order->finished_at = now();// 退款完成更新为已完结时间
                $order->save();

                // 更新交易记录信息
                $trade->status = OrderStatus::REFUNDED;
                $trade->save();

                // 学员移除
                if ($order->product_type === ProductType::PLAN) {
                    PlanMember::where('user_id', $refund->user_id)->where('plan_id', $product->id)->update(['status' => StudentStatus::EXITED]);
                } else {
                    ClassroomMember::where('user_id', $refund->user_id)->where('classroom_id', $product->id)->update(['status' => StudentStatus::EXITED]);
                }
            });
        } else if ($order->product_type === ProductType::RECHARGING) {
            // 充值订单
            DB::transaction(function () use ($refund, $result, $order, $trade, $product) {
                // 账户余额返还
                $user = $order->user;
                $user->increment('coin', $refund->applied_amount);
                // 更新退款表信息
                $refund->status = OrderStatus::REFUNDED;
                $refund->refunded_amount = $refund->applied_amount;
                $refund->handler_id = auth()->id();
                request('handled_reason') && $refund->handled_reason = request('handled_reason');
                $refund->handled_at = now();
                $refund->payment_callback = $result->toArray();
                $refund->save();

                // 更新订单信息
                $order->status = OrderStatus::REFUNDED;
                $order->finished_at = now();// 退款完成更新为已完结时间
                $order->save();

                // 更新交易记录信息
                $trade->status = OrderStatus::REFUNDED;
                $trade->save();
            });
        }
    }
}
