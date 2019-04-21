<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Feedback;

class FeedbackTransformer extends BaseTransformer
{
    public function transform(Feedback $model)
    {
        return [
            'id' => $model->id,
            'content' => $model->content,
            'email' => $model->email,
            'qq' => $model->qq,
            'wechat' => $model->wechat,
            'is_replied' => (boolean)$model->is_replied,
            'is_solved' => (boolean)$model->is_solved,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }
}