<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\Status;
use App\Models\Recharging;

class RechargingTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Recharging $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'price' => $model->price,
            'origin_price' => $model->origin_price,
            'coin' => $model->coin,
            'extra_coin' => $model->extra_coin,
            'income' => $model->income ?? 0,
            'bought_count' => $model->bought_count ?? 0,
            'status' => $model->status ?? Status::DRAFT,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 创建人
     *
     * @param Recharging $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeUser(Recharging $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }
}