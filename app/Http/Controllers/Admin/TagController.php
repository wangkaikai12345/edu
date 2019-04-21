<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Http\Transformers\TagTransformer;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\ModelHasTag;
use DB;

class TagController extends Controller
{
    /**
     * @SWG\Tag(name="admin/tag",description="标签")
     */

    /**
     * @SWG\Get(
     *  path="/admin/tag-groups/{group_id}/tags",
     *  tags={"admin/tag"},
     *  summary="标签列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TagPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(TagGroup $tagGroup)
    {
        $data = $tagGroup->tags()->sorted()->get();

        return $this->response->collection($data, new TagTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/tag-groups/{group_id}/tags",
     *  tags={"admin/tag"},
     *  summary="标签添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="body",name="body",type="object",required=true,@SWG\Schema(ref="#/definitions/Tag")),
     *  @SWG\Response(response=201,description="",@SWG\Schema(ref="#/definitions/Tag")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(TagRequest $request, TagGroup $tagGroup)
    {
        $input = $request->only(['name']);
        $item = $tagGroup->tags()->create($input);

        return $this->response->item($item, new TagTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Get(
     *  path="/admin/tag-group/{group_id}/tags/{tag_id}",
     *  tags={"admin/tag"},
     *  summary="标签详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Tag")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(TagGroup $tagGroup, $tag)
    {
        $tag = $tagGroup->tags()->findOrFail($tag);

        return $this->response->item($tag, new TagTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/tag-groups/{group_id}/tags/{tag_id}",
     *  tags={"admin/tag"},
     *  summary="标签更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Parameter(in="body",name="body",required=true,@SWG\Schema(ref="#/definitions/Tag")),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(TagRequest $request, TagGroup $tagGroup, $tag)
    {
        $tag = $tagGroup->tags()->findOrFail($tag);

        $input = $request->only(['name']);
        $tag->fill($input);
        $tag->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/tag-groups/{group_id}/tags/{tag_id}",
     *  tags={"admin/tag"},
     *  summary="标签删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(TagGroup $tagGroup, $tag)
    {
        $tag = $tagGroup->tags()->findOrFail($tag);
        // 去除 model_has_tags、tags、tag_group_tags
        DB::transaction(function () use ($tagGroup, $tag) {
            ModelHasTag::where('tag_id', $tag->id)->delete();
            $tag->delete();
        });

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/tag-groups/{group_id}/tags/delete",
     *  tags={"admin/tag"},
     *  summary="标签批量删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Parameter(in="formData",name="ids",type="array",@SWG\Items(type="string")),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function batchDelete(TagGroup $tagGroup, TagRequest $request)
    {
        $ids = $request->ids;

        DB::transaction(function () use ($ids, $tagGroup) {
            ModelHasTag::whereIn('tag_id', $ids)->delete();
            // 将标签移入回收站
            Tag::whereIn('id', $ids)->delete();
        });

        return $this->response->noContent();
    }
}
