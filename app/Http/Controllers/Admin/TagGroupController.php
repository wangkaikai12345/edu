<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagGroupRequest;
use App\Http\Transformers\TagGroupTransformer;
use App\Models\TagGroup;

class TagGroupController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/tag-groups",
     *  tags={"admin/tag"},
     *  summary="标签组列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TagGroupPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(TagGroup $tagGroup)
    {
        $data = $tagGroup->sorted()->get();

        return $this->response->collection($data, new TagGroupTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/tag-groups/{group_id}",
     *  tags={"admin/tag"},
     *  summary="标签组详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/TagGroup")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(TagGroup $tagGroup)
    {
        return $this->response->item($tagGroup, new TagGroupTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/tag-groups/{group_id}",
     *  tags={"admin/tag"},
     *  summary="标签组添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/TagGroup")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(TagGroupRequest $request)
    {
        $group = new TagGroup();
        $group->fill($request->all());
        $group->save();

        return $this->response->item($group, new TagGroupTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/tag-groups/{group_id}",
     *  tags={"admin/tag"},
     *  summary="标签组更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/TagGroup")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(TagGroupRequest $request, TagGroup $tagGroup)
    {
        $tagGroup->fill($request->all());
        $tagGroup->save();

        return $this->response->item($tagGroup, new TagGroupTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/admin/tag-groups/{group_id}",
     *  tags={"admin/tag"},
     *  summary="标签组删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/TagGroup")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(TagGroup $tagGroup)
    {
        if ($tagGroup->tags()->exists()) {
            $this->response->errorBadRequest(__('Child node exists.'));
        }
        $tagGroup->delete();

        return $this->response->noContent();
    }
}
