<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Transformers\CategoryTransformer;
use App\Models\Category;
use App\Models\CategoryGroup;
use Illuminate\Support\Facades\Artisan;

class CategoryController extends Controller
{
    /**
     * @SWG\Tag(name="admin/category",description="分类")
     */

    /**
     * @SWG\Get(
     *  path="/admin/category-groups/{group_id}/categories",
     *  tags={"admin/category"},
     *  summary="分类列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="group_id",type="integer",in="path",required=true,description="群组ID"),
     *  @SWG\Parameter(ref="#/parameters/CategoryQuery-name"),
     *  @SWG\Parameter(ref="#/parameters/CategoryQuery-parent_id"),
     *  @SWG\Parameter(ref="#/parameters/CategoryQuery-parent:name"),
     *  @SWG\Parameter(ref="#/parameters/Category-sort"),
     *  @SWG\Parameter(ref="#/parameters/Category-include"),
     *  @SWG\Response(response=200,ref="#/responses/CategoryResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(CategoryGroup $categoryGroup)
    {
        Artisan::call();
        !request('parent_id') && request()->offsetSet('parent_id', 0);

        $data = $categoryGroup->categories()->filtered()->sorted()->get();

        return $this->response->collection($data, new CategoryTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/category-groups/{group_id}/categories",
     *  tags={"admin/category"},
     *  summary="分类添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="group_id",type="integer",in="path",required=true,description="群组ID"),
     *  @SWG\Parameter(ref="#/parameters/CategoryForm-name"),
     *  @SWG\Parameter(ref="#/parameters/CategoryForm-seq"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Category"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function store(CategoryGroup $categoryGroup, CategoryRequest $request)
    {
        $parentId = $request->parent_id ? Category::findOrFail($request->parent_id)->id : 0;

        $category = new Category($request->all());
        $category->category_group_id = $categoryGroup->id;
        $category->parent_id = $parentId;
        $category->save();

        return $this->response->item($category, new CategoryTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/admin/category-groups/{group_id}/categories/{category_id}",
     *  tags={"admin/category"},
     *  summary="分类更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="group_id",type="integer",in="path",required=true,description="组ID"),
     *  @SWG\Parameter(name="category_id",type="integer",in="path",required=true,description="分类ID"),
     *  @SWG\Parameter(ref="#/parameters/CategoryForm-name"),
     *  @SWG\Parameter(ref="#/parameters/CategoryForm-seq"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update(CategoryGroup $categoryGroup, $category, CategoryRequest $request)
    {
        $item = $categoryGroup->categories()->findOrFail($category);

        $item->fill($request->all());
        $item->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/category-groups/{group_id}/categories",
     *  tags={"admin/category"},
     *  summary="分类单个删除/批量删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="group_id",type="integer",in="path",required=true,description="组ID"),
     *  @SWG\Parameter(name="ids",in="formData",type="array",required=true,@SWG\Items(type="integer"),description="分类ID数组"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function destroy(CategoryRequest $request, CategoryGroup $categoryGroup)
    {
        $categoryGroup->categories()->whereIn('id', $request->ids)->delete();

        return $this->response->noContent();
    }
}
