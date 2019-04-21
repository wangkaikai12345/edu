<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\CategoryGroupTransformer;
use App\Models\CategoryGroup;

class CategoryGroupController extends Controller
{
    /**
     * @SWG\Tag(name="admin/category",description="分类")
     */

    /**
     * @SWG\Get(
     *  path="/admin/category-groups",
     *  tags={"admin/category"},
     *  summary="分类组列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/CategoryGroupQuery-name"),
     *  @SWG\Parameter(ref="#/parameters/CategoryGroup-sort"),
     *  @SWG\Parameter(ref="#/parameters/CategoryGroup-include"),
     *  @SWG\Response(response=200,ref="#/responses/CategoryGroupResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index(CategoryGroup $categoryGroup)
    {
        $data = $categoryGroup->sorted()->filtered()->get();

        return $this->response->collection($data, new CategoryGroupTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/category-groups/{group_id}",
     *  tags={"admin/category"},
     *  summary="分类组详情",
     *  description="",
     *  @SWG\Parameter(name="group_id",type="integer",in="path",required=true,description="群组ID"),
     *  @SWG\Parameter(ref="#/parameters/CategoryGroup-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/CategoryGroup"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function show(CategoryGroup $categoryGroup)
    {
        return $this->response->item($categoryGroup, new CategoryGroupTransformer());
    }
}
