<?php
/**
 * Created by PhpStorm.
 * Tag: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    public function transform(Tag $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }
}