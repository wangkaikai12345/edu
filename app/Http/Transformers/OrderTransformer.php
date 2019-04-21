<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Order;
use App\Models\User;

class OrderTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'coupon', 'product', 'trade', 'refund'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Order $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'price_amount' => $model->price_amount,
            'pay_amount' => $model->pay_amount,
            'currency' => $model->currency,
            'user_id' => $model->user_id,
            'seller_id' => $model->seller_id,
            'status' => $model->status,
            'trade_uuid' => $model->trade_uuid,
            'paid_amount' => $model->paid_amount,
            'paid_at' => $model->paid_at ? $model->paid_at->toDateTimeString() : null,
            'payment' => $model->payment,
            'finished_at' => $model->finished_at ? $model->finished_at->toDateTimeString() : null,
            'closed_at' => $model->closed_at ? $model->closed_at->toDateTimeString() : null,
            'closed_message' => $model->closed_message,
            'closed_user_id' => $model->closed_user_id,
            'refund_deadline' => $model->refund_deadline ? $model->refund_deadline->toDateTimeString() : null,
            'created_reason' => $model->created_reason,
            'product_id' => $model->product_id,
            'product_type' => $model->product_type,
            'coupon_code' => $model->coupon_code,
            'coupon_type' => $model->coupon_type,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 购买人
     */
    public function includeUser(Order $model)
    {
        $user = $model->user()->select(User::$baseFields)->first();

        return $this->setDataOrItem($user, new MessageUserTransformer());
    }

    /**
     * 优惠详情
     */
    public function includeCoupon(Order $model)
    {
        return $this->setDataOrItem($model->coupon, new CouponTransformer());
    }

    /**
     * 商品信息
     */
    public function includeProduct(Order $model)
    {
        $transformer = app()->make('App\\Http\\Transformers\\'.ucfirst($model->product_type).'Transformer');

        return $this->setDataOrItem($model->product, $transformer);
    }

    /**
     * 交易记录
     */
    public function includeTrade(Order $order)
    {
        return $this->setDataOrItem($order->trade, new TradeTransformer());
    }

    /**
     * 退款
     */
    public function includeRefund(Order $order)
    {
        return $this->setDataOrItem($order->refund, new RefundTransformer());
    }
}