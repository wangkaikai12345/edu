<?php

namespace App\Http\Controllers\Admin;

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

class RefundController extends Controller
{
    use PayInstance;

    /**
     * @SWG\Tag(name="admin/refund",description="退款")
     */

    /**
     * @SWG\Get(
     *  path="/admin/refunds",
     *  tags={"admin/refund"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-order_id"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-payment"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-payment_sn"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/RefundQuery-user:username"),
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
        $data = Refund::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new RefundTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/refunds/{refund_id}",
     *  tags={"admin/refund"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="refund_id",in="path",description="退款ID",type="integer",required=true),
     *  @SWG\Parameter(ref="#/parameters/Refund-include"),
     *  @SWG\Response(response=200,ref="#/definitions/Refund"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Refund $refund)
    {
        return $this->response->item($refund, new RefundTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/refunds/{refund_id}/audit",
     *  tags={"admin/refund"},
     *  summary="审核",
     *  description="审核成功后自动完成退款；当退款审核失败后，依然可以重新审核；",
     *  @SWG\Parameter(name="refund_id",in="path",type="integer",required=true,description="退款 ID"),
     *  @SWG\Parameter(name="agree",in="formData",type="boolean",required=true,default=true,description="同意true、不同意false"),
     *  @SWG\Parameter(name="handled_reason",in="formData",type="string",description="处理缘由"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Refund"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
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

            return $this->response->created($refund, new RefundTransformer())->setStatusCode(201);
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
     * @SWG\Post(
     *  path="/admin/refunds/{refund_id}/manual",
     *  tags={"admin/refund"},
     *  summary="手动设置退款成功",
     *  description="适用于退款成功，但微信回调失败的情况；本接口会首先查询是否成功退款，若成功退款，则自动执行审核成功逻辑",
     *  @SWG\Parameter(name="refund_id",in="path",type="integer",required=true,description="退款 ID"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Refund"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
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
     * 退款操作步骤
     *
     * @param Refund $refund
     * @param \Yansongda\Supports\Collection $result
     * @return \Dingo\Api\Http\Response
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
