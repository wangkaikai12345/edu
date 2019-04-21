<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/6/15
 * Time: 08:42
 */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TagTransformer;
use App\Models\TagGroup;

class TagController extends Controller
{
    /**
     * @SWG\Tag(name="web/tag",description="前台标签")
     */

    /**
     * @SWG\Get(
     *  path="/tag-groups/{group_id}/tags",
     *  tags={"web/tag"},
     *  summary="列表（全部）",
     *  @SWG\Parameter(name="group_id",in="path",required=true,type="integer"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TagResponse")
     * )
     */
    public function index(TagGroup $group)
    {
        $data = $group->tags()->sorted()->get();

        return $this->response->collection($data, new TagTransformer());
    }
}