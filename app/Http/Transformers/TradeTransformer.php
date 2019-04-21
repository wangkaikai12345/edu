<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Trade;

class TradeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'order'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Trade $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'amount' => $model->amount,
            'cash_amount' => $model->cash_amount,
            'coin_amount' => $model->coin_amount,
            'currency' => $model->currency,
            'order_id' => $model->order_id,
            'platform' => $model->platform,
            'platform_sn' => $model->platform_sn,
            'platform_type' => $model->platform_type,
            'rate' => $model->rate,
            'apply_refund_time' => $model->apply_refund_time,
            'refund_success_time' => $model->refund_success_time,
            'status' => $model->status,
            'trade_uuid' => $model->trade_uuid,
            'type' => $model->type,
            'user_id' => $model->user_id,
            'pay_time' => $model->pay_time ? $model->pay_time->toDateTimeString() : null,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];

    }

    /**
     * 用户信息
     */
    public function includeUser(Trade $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 订单信息
     */
    public function includeOrder(Trade $model)
    {
        return $this->setDataOrItem($model->order, new OrderTransformer());
    }
}