<?php

namespace App\Http\Controllers\Web;

use App\Enums\CouponStatus;
use App\Enums\ProductType;
use App\Enums\SettingType;
use App\Enums\Status;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\OrderRequest;
use App\Http\Transformers\OrderTransformer;
use App\Models\Coupon;
use App\Services\OrderUnique;
use Facades\App\Models\Setting;
use App\Models\Order;
use Ramsey\Uuid\Uuid;

class MyOrderController extends Controller
{
    /**
     * @SWG\Tag(name="web/order",description="订单")
     */

    /**
     * @SWG\Get(
     *  path="/my-orders",
     *  tags={"web/order"},
     *  summary="列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-price_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-pay_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-currency"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-trade_uuid"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-paid_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-paid_at"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-payment"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-finished_at"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-closed_at"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-refund_deadline"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-product_type"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-coupon_code"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/Order-sort"),
     *  @SWG\Parameter(ref="#/parameters/Order-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/OrderPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $data = auth()->user()->orders()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, (new OrderTransformer())->setDefaultIncludes(['coupon']));
    }

    /**
     * @SWG\Get(
     *  path="/my-orders/{order_id}",
     *  tags={"web/order"},
     *  summary="详情",
     *  @SWG\Parameter(name="order_id",in="path",required=true,type="integer"),
     *  @SWG\Parameter(ref="#/parameters/Order-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Order"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show($order)
    {
        $item = auth()->user()->orders()->findOrFail($order);

        return $this->response->item($item, new OrderTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/orders",
     *  tags={"web/order"},
     *  summary="创建订单",
     *  description="当点击购买时，生成的订单信息，在使用优惠后，如果金额小于 1 并大于 0 时，那么则会自动调整为 1 分",
     *  @SWG\Parameter(ref="#/parameters/OrderForm-product_type"),
     *  @SWG\Parameter(ref="#/parameters/OrderForm-product_id"),
     *  @SWG\Parameter(ref="#/parameters/OrderForm-coupon_code"),
     *  @SWG\Parameter(ref="#/parameters/OrderForm-currency"),
     *  @SWG\Response(response=201,description="meta信息支付方式是否后台配置以及是否为重复订单",ref="#/definitions/Order"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(OrderRequest $request)
    {
        // 检测是否订单重复
        $validator = new OrderUnique(auth()->id(), $request->product_type, $request->product_id);
        if ($validator->exists()) {
            $this->response->errorForbidden(__('Repeat submitting.'));
        } else {
            $validator->set();
        }

        $alipay = Setting::namespace(SettingType::ALI_PAY)['on'];
        $wechat = Setting::namespace(SettingType::WECHAT_PAY)['on'];

        $order = Order::where('user_id', auth()->id())
            ->where('status', OrderStatus::CREATED)
            ->where('product_type', $request->product_type)
            ->where('product_id', $request->product_id)
            ->first();
        if ($order) {
            return $this->response->item($order, new OrderTransformer())->setStatusCode(201)->setMeta([
                'repeat' => true,
                'alipay' => $alipay,
                'wechat' => $wechat,
            ]);
        }

        // 商品
        $product = resolve('App\\Models\\' . ucfirst($request->product_type))->find($request->product_id);

        if ($product->status != Status::PUBLISHED) {
            $this->response->errorForbidden(__('Unpublished product.'));
        }

        $title = $request->product_type === ProductType::PLAN
            ? $product->course->title . ' ' . $product->title
            : $product->title;

        // 优惠码
        if ($request->coupon_code) {
            $coupon = Coupon::find($request->coupon_code);
            // 状态
            if ($coupon->status === CouponStatus::USED) {
                $this->response->errorBadRequest(__('Coupon code has been used.'));
            }
            // 有效期
            if ($coupon->expired_at->lt(now())) {
                $this->response->errorBadRequest(__('Coupon code is out of date.'));
            }
            // 指定使用人
            if ($coupon->consumer_id && $coupon->consumer_id !== auth()->id()) {
                $this->response->errorBadRequest(__('Coupon code has been activated.'));
            }
            // 适用商品
            if ($coupon->product_type && $coupon->product_type !== $request->product_type) {
                $this->response->errorBadRequest(__('Coupon code does not apply.'));
            }
            if ($coupon->product_type && $coupon->product_id && $coupon->product_id !== (int)$request->product_id) {
                $this->response->errorBadRequest(__('Coupon code does not apply.'));
            }
            $priceAmount = $product->price;
            $payAmount = $coupon->calculatePrice($product->price);
        } else {
            $coupon = null;
            $priceAmount = $product->price;
            $payAmount = $product->price;
        }
        
        // 创建订单
        $order = new Order();
        $order->title = $title;
        $order->price_amount = ($request->currency == 'coin' ? $product->coin_price :$priceAmount);
        $order->pay_amount = ($request->currency == 'coin' ? $product->coin_price :$priceAmount);
        $order->currency = $request->currency ?? 'cny';
        $order->user_id = auth()->id();
        $order->status = OrderStatus::CREATED;
        $order->trade_uuid = generate_only();
        $order->product_id = $request->product_id;
        $order->product_type = $request->product_type;
        $order->coupon_code = $coupon ? $coupon->code : null;
        $order->coupon_type = $coupon ? $coupon->type : null;
        $order->save();
	if ($request->currency == 'coin') {
		return $this->response->item($order, new OrderTransformer())->setStatusCode(201)->setMeta([
            'is_coin' => true,
        ]);
	}
        return $this->response->item($order, new OrderTransformer())->setStatusCode(201)->setMeta([
            'repeat' => false,
            'alipay' => $alipay,
            'wechat' => $wechat,
        ]);
    }

    /**
     * @SWG\Put(
     *  path="/my-orders/{order_id}",
     *  tags={"web/order"},
     *  summary="取消",
     *  @SWG\Parameter(name="order_id",in="path",required=true,type="integer"),
     *  @SWG\Parameter(name="closed_message",in="formData",type="string",description="取消订单的原因"),
     *  @SWG\Response(response=204,description=""),
     *  @SWG\Response(response=403,description="处于已支付、退款中、退款后、已关闭、已完成的状态下的订单无法取消。"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update($order, OrderRequest $request)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($order);

        // 仅新创建订单可以撤销
        if ($order->status !== OrderStatus::CREATED) {
            $this->response->errorBadRequest(__('Only orders in created status can be recalled.'));
        }

        $order->closed_message = $request->closed_message;
        $order->closed_user_id = auth()->id();
        $order->closed_at = now();
        $order->status = OrderStatus::CLOSED;
        $order->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/my-orders/{order_id}",
     *  tags={"web/order"},
     *  summary="删除",
     *  description="只有已完成状态 和 已关闭状态才能删除",
     *  @SWG\Parameter(name="order_id",in="path",required=true,type="integer"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=403,description="只有已关闭状态才能删除"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy($order)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($order);

        // 仅允许已关闭订单删除
        if (!in_array($order->status, [OrderStatus::CLOSED])) {
            $this->response->errorBadRequest(__('Only orders in closed status can be deleted.'));
        }

        $order->delete();

        return $this->response->noContent();
    }
}
