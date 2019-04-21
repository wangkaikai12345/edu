<?php

namespace App\Http\Controllers\Admin\Recycle;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TagTransformer;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/recycle/tags",
     *  tags={"admin/tag"},
     *  summary="列表（回收站）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TagResponse")
     * )
     */
    public function index()
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        $data = Tag::onlyTrashed()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new TagTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/recycle/tags/{tag_id}",
     *  tags={"admin/tag"},
     *  summary="恢复（回收站）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update($tag)
    {
        $tag = Tag::onlyTrashed()->findOrFail($tag);

        $tag->restore();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/recycle/tags/{tag_id}",
     *  tags={"admin/tag"},
     *  summary="删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function destroy($tag)
    {
        $tag = Tag::onlyTrashed()->where('id', $tag)->firstOrFail();

        $tag->forceDelete();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/recycle/tags/delete",
     *  tags={"admin/tag"},
     *  summary="批量删除（回收站）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="group_id",type="integer",required=true),
     *  @SWG\Parameter(in="path",name="tag_id",type="integer",required=true),
     *  @SWG\Parameter(in="formData",name="ids",type="array",@SWG\Items(type="string")),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function batchDelete(Request $request)
    {
        Tag::onlyTrashed()->whereIn('id', $request->ids)->forceDelete();

        return $this->response->noContent();
    }
}
