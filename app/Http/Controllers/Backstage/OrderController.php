<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\OrderStatus;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Transformers\OrderTransformer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Recharging;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function all()
    {
        $data = Order::filtered()->sorted()->get();

        return $this->response->collection($data, new OrderTransformer());
    }


    /**
     * 订单列表页
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Order $order, Request $request)
    {
        $orders = $order->filtered(array_filter($request->all()))->sorted()->with('user')->paginate(self::perPage());

        return view('admin.order.index', compact('orders'));
    }


    /**
     * 详情
     *
     * @param Order $order
     * @return \Dingo\Api\Http\Response
     */
    public function show(Order $order)
    {
        $order->load('user');
        return view('admin.order.show', compact('order'));
    }


    public function update(Order $order, OrderRequest $request)
    {
        $order->pay_amount = (int)$request->pay_amount;
        $order->save();

        return $this->response->noContent();
    }

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
