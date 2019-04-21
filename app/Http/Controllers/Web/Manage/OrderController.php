<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\ProductType;
use App\Http\Transformers\OrderTransformer;
use App\Models\Order;
use App\Models\Plan;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/plans/{plan_id}/orders",
     *  tags={"web/order"},
     *  summary="课程版本订单列表",
     *  description="",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="课程版本"),
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
    public function index(Plan $plan)
    {
        $this->authorize('isPlanTeacher', $plan);

        $data = Order::where('product_type', ProductType::PLAN)->filtered()->sorted()->where('product_id', $plan->id)->paginate(self::perPage());

        return $this->response->paginator($data, new OrderTransformer());
    }
}
