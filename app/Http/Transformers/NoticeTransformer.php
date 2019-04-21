<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Notice;
use League\Fractal\TransformerAbstract;

class NoticeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Notice $model)
    {
        return [
            'id' => $model->id,
            'type' => $model->type,
            'user_id' => $model->user_id,
            'content' => $model->content,
            'started_at' => $model->started_at,
            'ended_at' => $model->ended_at,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 用户
     */
    public function includeUser(Notice $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }
}