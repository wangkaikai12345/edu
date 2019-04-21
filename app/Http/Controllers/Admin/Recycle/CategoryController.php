<?php

namespace App\Http\Controllers\Admin\Recycle;

use App\Http\Controllers\Controller;
use App\Http\Transformers\CategoryTransformer;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/recycle/categories",
     *  tags={"admin/category"},
     *  summary="分类列表（回收站）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/CategoryQuery-name"),
     *  @SWG\Parameter(ref="#/parameters/CategoryQuery-parent_id"),
     *  @SWG\Parameter(ref="#/parameters/CategoryQuery-parent:name"),
     *  @SWG\Parameter(ref="#/parameters/Category-sort"),
     *  @SWG\Parameter(ref="#/parameters/Category-include"),
     *  @SWG\Response(response=200,ref="#/responses/CategoryPagination"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index()
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        $data = Category::onlyTrashed()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new CategoryTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/recycle/categories/{category_id}",
     *  tags={"admin/category"},
     *  summary="分类恢复（回收站）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="category_id",type="integer",in="path",required=true,description="分类ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update($category)
    {
        $category = Category::onlyTrashed()->findOrFail($category);

        $category->restore();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/recycle/categories",
     *  tags={"admin/category"},
     *  summary="单个删除/批量删除（回收站）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="formData",name="ids",required=true,type="array",@SWG\Items(type="integer"),description="分类ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function destroy(Request $request)
    {
        Category::whereIn('id', $request->ids)->forceDelete();

        return $this->response->noContent();
    }
}
