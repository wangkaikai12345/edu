<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Refund;
use App\Models\User;

class RefundTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'handler', 'order'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Refund $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'order_id' => $model->order_id,
            'user_id' => $model->user_id,
            'status' => $model->status,
            'payment' => $model->payment,
            'payment_sn' => $model->payment_sn,
            'reason' => $model->reason,
            'currency' => $model->currency,
            'applied_amount' => $model->applied_amount,
            'refunded_amount' => $model->refunded_amount,
            'handled_at' => $model->handled_at,
            'handler_id' => $model->handler_id,
            'handler_reason' => $model->handler_reason,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 退款人
     */
    public function includeUser(Refund $model)
    {
        $user = $model->user()->select(User::$baseFields)->first();

        return $this->setDataOrItem($user, new UserTransformer());
    }

    /**
     * 处理人
     */
    public function includeHandler(Refund $model)
    {
        $user = $model->handler()->select(User::$baseFields)->first();

        return $this->setDataOrItem($user, new UserTransformer());
    }

    /**
     * 处理人
     */
    public function includeOrder(Refund $model)
    {
        return $this->setDataOrItem($model->order, new OrderTransformer());
    }
}