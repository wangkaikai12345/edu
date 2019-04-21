<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Category;

class CategoryTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['children'];

    public function transform(Category $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'icon' => $model->icon,
            'seq' => $model->seq,
            'parent_id' => $model->parent_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 子集
     *
     * @param Category $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeChildren(Category $model)
    {
        return $this->setDataOrItem($model->children, new CategoryTransformer());
    }
}