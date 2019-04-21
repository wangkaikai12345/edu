<?php

/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Follow;

class FollowTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['follow', 'user'];

    public function transform(Follow $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'follow_id' => $model->follow_id,
            'is_pair' => $model->is_pair,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 用户
     */
    public function includeUser(Follow $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 关注人
     */
    public function includeFollow(Follow $model)
    {
        return $this->setDataOrItem($model->follow, new UserTransformer());
    }
}