<?php
/**
 * Created by PhpStorm.
 * Coupon: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\CouponStatus;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Recharging;

class CouponTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'consumer', 'product'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Coupon $model)
    {
        return [
            'code' => $model->code,
            'batch' => $model->batch,
            'type' => $model->type,
            'value' => $model->value,
            'expired_at' => $model->expired_at ? $model->expired_at->toDateTimeString() : null,
            'user_id' => $model->user_id,
            'consumer_id' => $model->consumer_id,
            'consumed_at' => $model->consumed_at ? $model->consumed_at->toDateTimeString() : null,
            'status' => $model->status ?? CouponStatus::UNUSED,
            'product_type' => $model->product_type,
            'product_id' => $model->product_id,
            'remark' => $model->remark,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 创建人
     */
    public function includeUser(Coupon $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 消费者
     */
    public function includeConsumer(Coupon $model)
    {
        return $this->setDataOrItem($model->consumer, new UserTransformer());
    }

    /**
     * 版本
     */
    public function includeProduct(Coupon $model)
    {
        $product = $model->product;
        if (!$product) {
          return $this->null();
        }

        switch (get_class($product)) {
            case Plan::Class:
                return $this->setDataOrItem($model->product, new PlanTransformer());
                break;
            case Recharging::class:
                return $this->setDataOrItem($model->product, new RechargingTransformer());
                break;
            default:
                return $this->null();
        }
    }
}