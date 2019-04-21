<?php

namespace App\Jobs;

use App\Enums\Currency;
use App\Enums\JoinType;
use App\Enums\OrderStatus;
use App\Enums\Payment;
use App\Models\Course;
use App\Models\Order;
use App\Models\PlanMember;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class MigrateOldYdmaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $ydma;
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->ydma = DB::connection('ydma');
        $user = $this->user;
        try {

            DB::beginTransaction();

            // 用户手机号
            $phone = $user->tel;
            // 用户邮箱
            $email = $user->email;
            // 用户昵称
            $username = $user->nick_name;

            // 构建查询语句
            $markQuery = User::query()->where(compact('phone'));

            // 查询用户邮箱
            if (!empty($email)) {
                $markQuery->orWhere(compact('email'));
            }

            // 查询用户昵称
            if (!empty($username)) {
                $markQuery->orWhere(compact('username'));
            }

            // 用户存在跳过
            if (!$markQuery->exists()) {

                // 创建新用户
                $nowUser = $this->createUser($user);

                // 获取订单信息
                $orders = $this->orderItems($this->goods($this->orders($user->guid)));

                // 旧课程与新课程的ID一致，故放弃课程标题比对
                if (!$orders->isEmpty()) {

                    // 处理订单
                    foreach ($orders as $order) {

                        // 获取商品的信息
                        $plan = $this->getNowGood($order);

                        // 创建订单
                        $nowOrder = $this->createOrder($order, $plan, $nowUser);

                        // 支付订单加入学习
                        $this->payOrder($nowOrder);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
        }
    }


    /**
     * 创建用户
     *
     * @param $user
     * @return mixed
     */
    protected function createUser($user)
    {
        // 用户用户名
        $username = $user->nick_name;

        // 查询重复
        $mark = User::where(compact('username'))->exists();

        // 重复
        if ($mark) {
            $username .= '_' . $user->tel;
        }

        $params = ['username' => $username,
            'password' => bcrypt(123456),
            'phone' => $user->tel,
            'coin' => $this->coin($user->guid)
        ];

        if (!empty($user->email)) {
            $params['email'] = $user->email;
        }

        $user = User::create($params);

        $user->profile()->create([]);

        return $user;
    }

    /**
     * 创建订单
     *
     * @param $oldOrder
     * @param $plan
     * @param $user
     * @return \App\Models\Order
     */
    protected function createOrder($oldOrder, $plan, $user)
    {
        // 创建订单
        $order = new Order();
        $order->title = $oldOrder->goods_name;
        $order->price_amount = $plan->price;
        $order->pay_amount = $plan->price;
        $order->currency = 'cny';
        $order->user_id = $user->id;
        $order->status = \App\Enums\OrderStatus::CREATED;
        $order->trade_uuid = generate_only();
        $order->product_id = $plan->id;
        $order->product_type = 'plan';
        $order->coupon_code = null;
        $order->coupon_type = null;
        $order->save();

        return $order;
    }


    /**
     * 支付订单
     *
     * @param $order
     * @return mixed
     * @throws \Exception
     */
    protected function payOrder($order)
    {
        // 免费订单
        if ($order->pay_amount === 0) {
            $this->freeOrder($order);
        }

        $order->payment = 'alipay';
        $order->save();

        $trade = new Trade();
        $trade->title = $order->title;
        $trade->order_id = $order->id;
        $trade->trade_uuid = $order->trade_uuid;
        $trade->status = OrderStatus::SUCCESS;
        $trade->currency = Currency::CNY;
        $trade->user_id = $order->user_id;
        $trade->payment_callback = [];
        $trade->paid_at = now();
        $trade->payment = 'alipay';
        $trade->payment_sn = Uuid::uuid4()->getHex();
        // 根据支付类型对已支付总金额处理
        $trade->paid_amount = $order->pay_amount;
        // 根据订单模型类型对交易记录的类型进区分
        $trade->type = 'purchase';
        $trade->save();

        return $order;
    }


    /**
     * 创建免费订单
     *
     * @param Order $order
     * @return Order
     */
    protected function freeOrder(Order $order)
    {

        // 修改订单状态
        $order->status = OrderStatus::FINISHED;
        $order->paid_amount = 0;
        $order->paid_at = now();
        $order->payment = Payment::FREE;
        $order->finished_at = now();
        $order->refund_deadline = now();
        $order->save();


        $member = new PlanMember();
        $member->course_id = $order->product->course_id;
        $member->plan_id = $order->product->id;
        $member->user_id = $order->user_id;
        $member->join_type = JoinType::PURCHASE;
        $member->deadline = null;
        $member->order_id = $order->id;
        $member->save();

        return $order;
    }


    /**
     * 获取教学版本信息
     *
     * @param $order
     * @return mixed
     */
    public function getNowGood($order)
    {
        // 获取课程信息
        $course = Course::find($order->id);
        // 获取教学版本
        return $course->default_plan;
    }


    /**
     * 获取用户订单ID数据
     *
     * @param $guid
     * @return \Illuminate\Support\Collection
     */
    public function orders($guid)
    {
        return DB::connection('ydma')
            ->table('order')
            ->where(compact('guid'))
            ->where(['type' => 2, 'order_status' => 1])
            ->pluck('order_id');
    }


    /**
     * 获取商品ID数据
     *
     * @param $orderIds
     * @return \Illuminate\Support\Collection
     */
    public function goods($orderIds)
    {
        // 获取订单的商品ID
        return DB::connection('ydma')
            ->table('rel_order')->whereIn('order_id', $orderIds)
            ->pluck('goods_id');
    }


    /**
     * 获取商品信息
     *
     * @param $goodIds
     * @return \Illuminate\Support\Collection
     */
    public function orderItems($goodIds)
    {
        // 获取商品的价格
        return DB::connection('ydma')
            ->table('qiniu_goods')
            ->whereIn('id', $goodIds)
            ->select('goods_money', 'id', 'goods_name')
            ->get();
    }

    /**
     * 获取用户虚拟币
     *
     * @param $guid
     * @return float|int
     */
    public function coin($guid)
    {
        // 获取老用户的金元宝数量
        $coin = DB::connection('ydma')
            ->table('user_account')
            ->where(compact('guid'))
            ->select('gold', 'silver')
            ->first();
        return ($coin->gold + ceil($coin->silver / 100)) * 100;
    }
}
