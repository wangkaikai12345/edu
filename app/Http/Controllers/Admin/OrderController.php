<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Transformers\OrderTransformer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Recharging;

/**
 * @SWG\Path(
 *   path="/admin/orders/{order_id}",
 *   @SWG\Parameter(name="order_id",type="integer",in="path",required=true,description="订单ID")
 * )
 */

class OrderController extends Controller
{
    /**
     * @SWG\Tag(name="admin/order",description="后台订单")
     */

    /**
     * @SWG\GET(
     *  path="/admin/all-orders",
     *  tags={"admin/order"},
     *  summary="订单全部列表",
     *  description="导出",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-price_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-pay_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-currency"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-user_id"),
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
     *  @SWG\Response(response=200,ref="#/responses/OrderResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function all()
    {
        $data = Order::filtered()->sorted()->get();

        return $this->response->collection($data, new OrderTransformer());
    }

    /**
     * @SWG\GET(
     *  path="/admin/orders",
     *  tags={"admin/order"},
     *  summary="订单分页列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-price_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-pay_amount"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-currency"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/OrderQuery-user_id"),
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
     *  @SWG\Response(response=200,ref="#/responses/OrderPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Order $order)
    {
        $data = $order->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new OrderTransformer());
    }

    /**
     * @SWG\GET(
     *  path="/admin/orders/{order_id}",
     *  tags={"admin/order"},
     *  summary="订单详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/Order-include"),
     *  @SWG\Response(response=200,ref="#/definitions/Order"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Order $order)
    {
        return $this->response->item($order, new OrderTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/orders/{order_id}",
     *  tags={"admin/order"},
     *  summary="订单改价",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/OrderForm-pay_amount"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Order $order, OrderRequest $request)
    {
        $order->pay_amount = (int)$request->pay_amount;
        $order->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/orders/{order_id}",
     *  tags={"admin/order"},
     *  summary="订单关闭",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/OrderForm-closed_message"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Order $order)
    {
        if ($order->status !== OrderStatus::CREATED) {
            $this->response->errorBadRequest(__('Orders in created status can be cancel.'));
        }
        $order->closed_user_id = auth()->id();
        $order->closed_at = now();
        $order->closed_message = request('closed_message');
        $order->save();

        return $this->response->noContent();
    }
}
