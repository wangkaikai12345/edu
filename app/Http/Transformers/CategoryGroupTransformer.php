<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\CategoryGroup;

class CategoryGroupTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['categories'];

    public function transform(CategoryGroup $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 分类
     *
     * @param CategoryGroup $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeCategories(CategoryGroup $model)
    {
        return $this->setDataOrItem($model->categories, new CategoryTransformer());
    }
}