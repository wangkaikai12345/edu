<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/6/15
 * Time: 08:42
 */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TagGroupTransformer;
use App\Models\TagGroup;

class TagGroupController extends Controller
{
    /**
     * @SWG\Tag(name="web/tag-group",description="前台标签群组")
     */

    /**
     * @SWG\Get(
     *  path="/tag-groups",
     *  tags={"web/tag-group"},
     *  summary="列表（全部）",
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TagGroupResponse")
     * )
     */
    public function index(TagGroup $group)
    {
        $data = $group->sorted()->get();

        return $this->response->collection($data, new TagGroupTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/tag-groups/{group_id}",
     *  tags={"web/tag-group"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  summary="详情",
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/TagGroup"))
     * )
     */
    public function show(TagGroup $group)
    {
        return $this->response->item($group, new TagGroupTransformer());
    }
}