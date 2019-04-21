<?php

namespace App\Http\Controllers\Front;

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
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    use PayInstance;
    use JoinTrait;

    /**
     * 充值虚拟币订单支付
     *
     * @param Request $request
     * @author 王凯
     */
    public function coinStore(Request $request)
    {
        // 表单验证
        $this->validate($request, [
            'price_amount' => ['required'],
            'payment' => ['required', new CustomEnumRule(Payment::class),]
        ]);

        // 创建订单
        $order = new Order();
        $order->title = '现金充值虚拟币';
        $order->price_amount = $request->price_amount * 100;
        $order->pay_amount = $request->price_amount * 100;
        $order->currency = 'cny';
        $order->user_id = auth('web')->id();
        $order->status = OrderStatus::CREATED;
        $order->trade_uuid = generate_only();
        $order->product_type = ProductType::RECHARGING;
        $order->save();

        // 支付，生成二维码
        $response = $this->payCnyOrder($order);

        $response['order_id'] = $order->id;

        return ajax('200', '操作成功', $response);
    }

    /**
     * 订单支付
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_id' => ['required', 'exists:orders,id',],
//            'payment' => ['required', new CustomEnumRule(Payment::class),]
            'payment' => ['required', 'in:alipay,wechat,coin,free']
        ]);

        // 课程订单 或 充值订单
        $order = Order::findOrFail($request->order_id);

        // 验证订单是否为正常订单
        $this->checkValidity($order);

        // 免费订单
        if ($order->pay_amount === 0) {
            $this->freeOrder($order);
            return ajax('200', '操作成功', ['type' => 'free', 'status' => true]);
        }

        // 现金支付的付费订单 or 虚拟币支付的付费订单
        if ($order->currency === Currency::CNY) {
            // 保存用户的支付方式（回调失败，则依靠此确认第三方支付订单），同时在回调时自动更新支付方式
            $order->payment = request('payment');
            $order->save();

            // 手机端微信支付
            if (is_show_mobile_page() && $order->payment == 'wechat' && $request->isWechat == 'mo') {
                $config = config('wechat.payment.default');

                $app = Factory::payment($config);

                $result = $app->order->unify([
                    'body' => $order->title,
                    'out_trade_no' => $order->trade_uuid,
                    'total_fee' => $order->pay_amount,
                    'spbill_create_ip' => get_client_ip() ?? '',
                    'notify_url' => route('pay.notify', 'wechat'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type' => 'MWEB',
//                    'redirect_url' => urlencode(route('plans.intro', [$order->product->course, $order->product])),
                ]);

                if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                    return ajax('200', '操作成功', ['type' => 'mweb', 'mweb_url' => $result['mweb_url']]);
                } else {
                    return ajax('400', '数据异常');
                }

            } else {
                $response = $this->payCnyOrder($order);
            }
        } else {
            $response = $this->payCoinOrder($order);
        }

        return ajax('200', '操作成功', $response);
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
            return ajax('400', '订单已经过期');
        }

        // 判断订单状态，当为不可支付状态时，则抛出异常响应
        switch ($order->status) {
            case OrderStatus::CREATED:
                // 空
                break;
            case OrderStatus::PAID:
                return ajax('400', __('Paid order.'));
                break;
            case OrderStatus::REFUNDING:
                return ajax('400', __('Refunding order.'));
                break;
            case OrderStatus::REFUNDED:
                return ajax('400', __('Refunded order.'));
                break;
            case OrderStatus::REFUND_DISAGREE:
                return ajax('400', __('Rejected refund.'));
                break;
            case OrderStatus::CLOSED:
                return ajax('400', __('Rejected refund.'));
                break;
            case OrderStatus::SUCCESS:
                return ajax('400', __('Successful order.'));
                break;
            case OrderStatus::FINISHED:
                return ajax('400', __('Completed order.'));
                break;
            default:
                return ajax('400', __('Abnormal order.'));
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
                return ajax('400', __('Coin\'s payment method is not available.'));
                break;
            default:
                return ajax('400', __('Unknown payment.'));
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
            return ajax('400', __('Coin only in used for course and classroom.'));
        }

        // 查询是否账户余额充足
        if (auth('web')->user()->coin < $order->pay_amount) {
            return ajax('400', __('Account balance is not enough.'));
        }

        $product = $order->product;

        Trade::unsetEventDispatcher();
        DB::transaction(function () use ($order, $product) {
            // 账户虚拟币扣除
            auth('web')->user()->decrement('coin', $order->pay_amount);

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

            if ($order->product_type == ProductType::PLAN) {
                // 将用户移入到版本之中
                $this->joinPlan($order);
            } else if ($order->product_type == ProductType::CLASSROOM) {
                // 将用户移入到班级之中
                $this->joinClassroom($order);
            }
        });

        return ['type' => 'coin', 'status' => true];
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
        info('回调' . $type);
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

        info('回调订单' . $order);

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
