<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/6/15
 * Time: 08:42
 */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\CategoryGroupTransformer;
use App\Models\CategoryGroup;

class CategoryGroupController extends Controller
{
    /**
     * @SWG\Tag(name="web/category-group",description="前台分类群组")
     */

    /**
     * @SWG\Get(
     *  path="/category-groups",
     *  tags={"web/category-group"},
     *  summary="列表（全部）",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/CategoryGroup-include"),
     *  @SWG\Parameter(ref="#/parameters/CategoryGroup-sort"),
     *  @SWG\Response(response=200,ref="#/responses/CategoryGroupResponse")
     * )
     */
    public function index()
    {
        $data = CategoryGroup::sorted()->get();

        return $this->response->collection($data, new CategoryGroupTransformer());
    }
}