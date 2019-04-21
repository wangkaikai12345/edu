<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/6/15
 * Time: 08:42
 */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\CategoryTransformer;
use App\Models\CategoryGroup;

class CategoryController extends Controller
{
    /**
     * @SWG\Tag(name="web/category",description="前台分类相关")
     */

    /**
     * @SWG\Get(
     *   path="/category-groups/{group_id}/categories",
     *   tags={"web/category"},
     *   summary="列表（全部）",
     *   description="默认会包含children子集",
     *   @SWG\Parameter(name="group_id",in="path",type="integer",required=true,description="分类组ID"),
     *   @SWG\Parameter(ref="$/parameters/Category-include"),
     *   @SWG\Parameter(ref="#/parameters/Category-sort"),
     *   @SWG\Response(response=200,ref="#/responses/CategoryResponse"),
     * )
     */
    public function index(CategoryGroup $group)
    {
        $data = $group->categories()->where('parent_id', 0)->sorted()->get();

        return $this->response->collection($data, (new CategoryTransformer())->setDefaultIncludes(['children']));
    }
}