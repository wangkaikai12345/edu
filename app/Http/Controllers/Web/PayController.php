<?php

namespace App\Http\Controllers\Web;

use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\Payment;
use App\Enums\ProductType;
use App\Enums\TradeType;
use App\Http\Controllers\Controller;
use App\Traits\JoinTrait;
use App\Traits\PayInstance;
use App\Models\Order;
use App\Models\Trade;
use App\Rules\CustomEnumRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    use PayInstance;
    use JoinTrait;

    /**
     * @SWG\Tag(name="web/pay",description="支付")
     */

    /**
     * @SWG\Post(
     *  path="/pay",
     *  tags={"web/pay"},
     *  summary="订单支付",
     *  description="虚拟币仅能用于购买课程，不能用于充值订单",
     *  @SWG\Parameter(in="formData",name="order_id",type="integer",description="订单ID"),
     *  @SWG\Parameter(in="formData",name="payment",type="string",enum={"alipay","wechat","coin"},description="支付宝/微信/虚拟币"),
     *  @SWG\Response(response=201,description="ok",@SWG\Schema(
     *      @SWG\Property(property="type",enum={"code","status"},description="两种类型：code即二维码，status即状态"),
     *      @SWG\Property(property="code",example="weixin://123123.qq.com",description="当类型为code时，此处即 URL 地址（微信、支付宝军返回二维码）"),
     *      @SWG\Property(property="status",example="1",enum={0,1},description="状态，当为虚拟币购买时，则会返回此项，即成功 or 失败"),
     *  )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_id' => ['required', 'exists:orders,id',],
            'payment' => ['required', new CustomEnumRule(Payment::class),]
        ]);

        // 课程订单 或 充值订单
        $order = Order::find($request->order_id);

        // 验证订单是否为正常订单
        $this->checkValidity($order);

        // 免费订单
        if ($order->pay_amount === 0) {
            $this->freeOrder($order);
            return $this->response->array(['type' => 'status', 'status' => true])->setStatusCode(201);
        }

        // 现金支付的付费订单 or 虚拟币支付的付费订单
        if ($order->currency === Currency::CNY) {
            // 保存用户的支付方式（回调失败，则依靠此确认第三方支付订单），同时在回调时自动更新支付方式
            $order->payment = request('payment');
            $order->save();
            $response = $this->payCnyOrder($order);
        } else {
            $response = $this->payCoinOrder($order);
        }

        return $this->response->array($response);
    }

    /**
     * 检查订单是否过期、状态是否异常
     *
     * @param Order $order
     * @return void
     */
    protected function checkValidity(Order $order)
    {
        // 判断订单是否已过期
        if ($order->created_at->lt(now()->subMinutes(Order::$expires))) {
            $this->response->errorBadRequest(__('Expired order.'));
        }

        // 判断订单状态，当为不可支付状态时，则抛出异常响应
        switch ($order->status) {
            case OrderStatus::CREATED:
                // 空
                break;
            case OrderStatus::PAID:
                $this->response->errorForbidden(__('Paid order.'));
                break;
            case OrderStatus::REFUNDING:
                $this->response->errorForbidden(__('Refunding order.'));
                break;
            case OrderStatus::REFUNDED:
                $this->response->errorForbidden(__('Refunded order.'));
                break;
            case OrderStatus::REFUND_DISAGREE:
                $this->response->errorForbidden(__('Rejected refund.'));
                break;
            case OrderStatus::CLOSED:
                $this->response->errorForbidden(__('Closed order.'));
                break;
            case OrderStatus::SUCCESS:
                $this->response->errorForbidden(__('Successful order.'));
                break;
            case OrderStatus::FINISHED:
                $this->response->errorForbidden(__('Completed order.'));
                break;
            default:
                $this->response->errorForbidden(__('Abnormal order.'));
        }
    }

    /**
     * 免费订单
     *
     * @param Order $order
     * @throws \Throwable
     */
    protected function freeOrder(Order $order)
    {
        DB::transaction(function () use ($order) {
            // 修改订单状态
            $order->status = OrderStatus::FINISHED;
            $order->paid_amount = 0;
            $order->paid_at = now();
            $order->payment = Payment::FREE;
            $order->finished_at = now();
            $order->refund_deadline = now();
            $order->save();

            if ($order->product_type === ProductType::PLAN) {
                // 将用户移入到版本之中
               $this->joinPlan($order);
            } else if ($order->product_type === ProductType::CLASSROOM) {
                // 将用户移入到班级之中
                $this->joinClassroom($order);
            }
        });
    }

    /**
     * 现金付费订单（含课程订单、充值订单）
     *
     * @param Order $order
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function payCnyOrder(Order $order)
    {
        switch (request('payment')) {
            case Payment::ALIPAY:
                $pay = $this->payInstance(Payment::ALIPAY);
                /**
                 * out_trade_no:订单号
                 * total_amount:金额(单位：元)
                 * subject:商品标题
                 */
                $response = $pay->scan([
                    'out_trade_no' => $order->trade_uuid,
                    'total_amount' => $order->pay_amount / 100,
                    'subject' => $order->title
                ]);
                return ['type' => 'qr_code', 'code' => $response->qr_code];
                break;
            case Payment::WECHAT:
                $pay = $this->payInstance(Payment::WECHAT);
                /**
                 * out_trade_no:订单号
                 * total_fee:金额(单位：分)
                 * body:商品标题
                 */
                $response = $pay->scan([
                    'out_trade_no' => $order->trade_uuid,
                    'total_fee' => $order->pay_amount,
                    'body' => $order->title
                ]);
                return ['type' => 'qr_code', 'code' => $response->code_url];
                break;
            case Payment::COIN:
                $this->response->errorBadRequest(__('Coin\'s payment method is not available.'));
                break;
            default:
                $this->response->errorBadRequest(__('Unknown payment.'));
        }
    }

    /**
     * 虚拟币付费订单（仅课程、班级订单）
     *
     * @param Order $order
     * @return
     */
    protected function payCoinOrder(Order $order)
    {
        // 虚拟币仅能用于购买课程
        if ($order->product_type !== ProductType::PLAN) {
            $this->response->errorBadRequest(__('Coin only in used for course and classroom.'));
        }

        // 查询是否账户余额充足
        if (auth()->user()->coin < $order->pay_amount) {
            $this->response->errorBadRequest(__('Account balance is not enough.'));
        }

        $product = $order->product;

        Trade::unsetEventDispatcher();
        DB::transaction(function () use ($order, $product) {
            // 账户虚拟币扣除
            auth()->user()->decrement('coin', $order->pay_amount);

            // 交易记录
            $trade = new Trade();
            $trade->title = $order->title;
            $trade->order_id = $order->id;
            $trade->trade_uuid = $order->trade_uuid;
            $trade->status = OrderStatus::SUCCESS;
            $trade->currency = Currency::COIN;
            $trade->user_id = $order->user_id;
            $trade->payment_callback = null;
            $trade->paid_at = now();
            $trade->payment = Payment::COIN;
            $trade->payment_sn = $order->trade_uuid;
            $trade->paid_amount = $order->pay_amount;
            $trade->type = TradeType::PURCHASE;
            $trade->save();

            // 订单更新
            $order->status = OrderStatus::SUCCESS;
            $order->paid_at = now();
            $order->paid_amount = $trade->paid_amount;
            $order->payment = $trade->payment;
            $order->finished_at = $trade->paid_at->addDays(Order::$refundDays);// 需要计算
            $order->refund_deadline = $trade->paid_at->addDays(Order::$refundDays);// 需要计算
            $order->save();

            if ($order->product_type === ProductType::PLAN) {
                // 将用户移入到版本之中
                $this->joinPlan($order);
            } else if ($order->product_type === ProductType::CLASSROOM) {
                // 将用户移入到班级之中
                $this->joinClassroom($order);
            }
        });

        return ['type' => 'status', 'status' => true];
    }

    /**
     * 支付异步回调
     *
     * @param string $type
     * @return mixed
     * @throws \Throwable
     */
    public function notify(string $type)
    {
        // 验签
        try {
            $pay = $this->payInstance($type);
            $result = $pay->verify();
        } catch (\Yansongda\Pay\Exceptions\InvalidGatewayException $exception) {
            \Log::error($exception);
            return 'failed';
        } catch (\Yansongda\Pay\Exceptions\InvalidSignException $exception) {
            \Log::error($exception);
            return 'failed';
        } catch (\Yansongda\Pay\Exceptions\InvalidConfigException $exception) {
            \Log::error($exception);
            return 'failed';
        } catch (\Yansongda\Pay\Exceptions\GatewayException $exception) {
            \Log::error($exception);
            return 'failed';
        } catch (\Exception $exception) {
            \Log::error($exception);
            return 'failed';
        }

        // 订单查询
        $order = Order::where('trade_uuid', $result->out_trade_no)->first();
        if (!$order) {
            info("[{$type}] 未知支付回调：");
            info($result);
            return $pay->success();
        }

        // 比对是否订单金额与支付金额一致
        $bool = ($type === Payment::ALIPAY && ($result->total_amount * 100) == $order->pay_amount) || $type === Payment::WECHAT && (int)$result->total_fee === $order->pay_amount;
        if ($bool) {
            $trade = new Trade();
            $trade->title = $order->title;
            $trade->order_id = $order->id;
            $trade->trade_uuid = $order->trade_uuid;
            $trade->status = OrderStatus::SUCCESS;
            $trade->currency = Currency::CNY;
            $trade->user_id = $order->user_id;
            $trade->payment_callback = $result->toArray();
            $trade->paid_at = now();
            $trade->payment = $type;
            $trade->payment_sn = $result->out_trade_no;
            // 根据支付类型对已支付总金额处理
            $trade->paid_amount = $type === Payment::ALIPAY ? $result->total_amount * 100 : $result->total_fee;
            // 根据订单模型类型对交易记录的类型进区分
            $trade->type = ($order->product_type === ProductType::PLAN || $order->product_type === ProductType::CLASSROOM)
                ? TradeType::PURCHASE
                : TradeType::RECHARGE;
            $trade->save();
            return $pay->success();
        }
        info('回调数据与订单金额不符。');

        return 'failed';
    }
}
