<?php

namespace App\Http\Controllers\Front;

use App\Enums\CouponStatus;
use App\Enums\ProductType;
use App\Enums\SettingType;
use App\Enums\Status;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\OrderRequest;
use App\Models\Coupon;
use App\Services\OrderUnique;
use Facades\App\Models\Setting;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    /**
     * 我的订单
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
        $orders = auth('web')->user()
            ->orders()
            ->filtered($search)
            ->where('product_type', '!=', 'recharging')
            ->sorted()
            ->with('product')
            ->paginate(config('theme.plan_detail'))
            ->appends($request->only(['title']));

        return frontend_view('personal.order', compact('orders'));
    }

    /**
     * 获取商品的订单详情
     *
     * @param Request $request
     * @author 王凯
     */
    public function info($id)
    {
        $order = Order::where(['product_type' => 'plan', 'product_id' => $id, 'user_id' => auth('web')->id()])->first() ?? '';

        return ajax('200', '', $order);
    }

    /**
     * 查看订单状态
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function show($id)
    {
        $order = auth('web')->user()->orders()->findOrFail($id);

        return ajax('200', '订单信息获取成功', $order);
    }

    /**
     * 订单详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function edit($order)
    {
        if (auth('web')->user()->isAdmin() || auth('web')->user()->isTeacher()) {

            $order = Order::findOrFail($order);
        } else {
            $order = auth('web')->user()->orders()->findOrFail($order);
        }

        return frontend_view('personal.details-modal', compact('order'));
    }

    /**
     * 创建订单
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function store(OrderRequest $request)
    {
        // 检测是否订单重复
//        $validator = new OrderUnique(auth('web')->id(), $request->product_type, $request->product_id);
//
//        if ($validator->exists()) {
//            return ajax('400', '请稍后再试...');
//        } else {
//            $validator->set();
//        }

        // 获取支付配置
        $alipay = Setting::namespace(SettingType::ALI_PAY)['on'];
        $wechat = Setting::namespace(SettingType::WECHAT_PAY)['on'];

        $order = Order::where('user_id', auth('web')->id())
            ->where('status', OrderStatus::CREATED)
            ->where('product_type', $request->product_type)
            ->where('product_id', $request->product_id)
            ->first();

        if ($order) {
            return ajax('200', '订单信息获取成功', [
                'alipay' => $alipay,
                'wechat' => $wechat,
                'order' => $order
            ]);
        }

        // 商品
        $product = resolve('App\\Models\\' . ucfirst($request->product_type))->find($request->product_id);

        if ($product->status != Status::PUBLISHED) {
            return ajax('400', '商品不存在');
        }

        // 组装订单标题
        $title = $request->product_type === ProductType::PLAN
            ? $product->course->title . ' ' . $product->title
            : $product->title;

        // 优惠码
        if ($request->coupon_code) {

            $coupon = Coupon::find($request->coupon_code);
            // 状态
            if ($coupon->status === CouponStatus::USED) return ajax('400', '优惠券已经被使用');
            // 有效期
            if ($coupon->expired_at->lt(now())) return ajax('400', '优惠券已经过期了');
            // 指定使用人
            if ($coupon->consumer_id && $coupon->consumer_id !== auth('web')->id()) return ajax('400', '优惠码已被激活，无法使用');
            // 适用商品
            if ($coupon->product_type && $coupon->product_type !== $request->product_type) return ajax('400', '优惠码不适用于本商品');
            if ($coupon->product_type && $coupon->product_id && $coupon->product_id !== (int)$request->product_id) {
                return ajax('400', '优惠码不适用于本商品');
            }
            $priceAmount = $product->price;
            $payAmount = $coupon->calculatePrice($product->price);
        } else {
            $coupon = null;
            $priceAmount = $product->price;
            $payAmount = $product->price;
        }

        // 虚拟币创建订单验证
        if ($request->currency == 'coin' && $product->coin_price > auth('web')->user()->coin) {
            return ajax('400', '您的虚拟币余额不足');
        }

        // 创建订单
        $order = new Order();
        $order->title = $title;
        $order->price_amount = ($request->currency == 'coin' ? $product->coin_price :$priceAmount);
        $order->pay_amount = ($request->currency == 'coin' ? $product->coin_price :$payAmount);
        $order->currency = $request->currency ?? 'cny';
        $order->user_id = auth('web')->id();
        $order->status = OrderStatus::CREATED;
//        $order->trade_uuid = Uuid::uuid4()->getHex();
        $order->trade_uuid = generate_only();
        $order->product_id = $request->product_id;
        $order->product_type = $request->product_type;
        $order->coupon_code = $coupon ? $coupon->code : null;
        $order->coupon_type = $coupon ? $coupon->type : null;
        $order->save();

        return ajax('200', '订单创建成功', [
            'is_coin' => $request->currency == 'coin',
            'alipay' => $alipay,
            'wechat' => $wechat,
            'order'  => $order,
        ]);
    }

    /**
     * 取消订单
     *
     * @param $id
     * @param OrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function update($id, OrderRequest $request)
    {
        $order = auth('web')->user()->orders()->findOrFail($id);

        // 仅新创建订单可以撤销
        if ($order->status !== OrderStatus::CREATED) return back()->with('danger', '订单不可以取消');

        $order->closed_message = $request->closed_message;
        $order->closed_user_id = auth('web')->id();
        $order->closed_at = now();
        $order->status = OrderStatus::CLOSED;
        $order->save();

        return back()->with('success', '取消订单成功');
    }

    /**
     * 删除订单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function destroy($id)
    {
        $order = auth('web')->user()->orders()->findOrFail($id);

        // 仅允许已关闭订单删除
        if (!in_array($order->status, [OrderStatus::CLOSED])) return back()->with('danger', '订单不可以删除');

        $order->delete();

        return back()->with('success', '删除订单成功');
    }

}