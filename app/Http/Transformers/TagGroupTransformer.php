<?php
/**
 * Created by PhpStorm.
 * TagGroup: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\TagGroup;

class TagGroupTransformer extends BaseTransformer
{
    protected $availableIncludes = [];

    protected $defaultIncludes = ['tags'];

    public function transform(TagGroup $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'tag_num' => $model->tag_num ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 允许包含标签
     */
    public function includeTags(TagGroup $model)
    {
        return $this->setDataOrItem($model->tags, new TagTransformer());
    }
}