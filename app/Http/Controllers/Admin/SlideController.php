<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SlideRequest;
use App\Http\Transformers\SlideTransformer;
use App\Models\Slide;
use DB;

class SlideController extends Controller
{
    /**
     * @SWG\Tag(name="admin/slide",description="后台轮播图")
     */

    /**
     * @SWG\Get(
     *  path="/admin/slides",
     *  tags={"admin/slide"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="include",in="path",required=true,in="query",type="string",description="是否包含关联数据：user"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/SlideResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        if (request()->has('sort')) {
            request()->offsetSet('sort', 'seq,asc');
        }

        $data = Slide::sorted()->get();

        return $this->response->collection($data, new SlideTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/slides/{slide_id}",
     *  tags={"admin/slide"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="slide_id",in="path",required=true,type="integer"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Slide")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Slide $slide)
    {
        return $this->response->item($slide, new SlideTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/slides",
     *  tags={"admin/slide"},
     *  summary="添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="body",in="body",type="object",@SWG\Schema(ref="#/definitions/Slide")),
     *  @SWG\Response(response=201,description="",@SWG\Schema(ref="#/definitions/Slide")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(SlideRequest $request, Slide $slide)
    {
        $slide->user_id = auth()->id();

        // 查询最大 seq
        $max = Slide::max('seq');
        $slide->seq = $max ? $max + 1 : 1;
        $slide->fill($request->all());
        $slide->save();

        return $this->response->item($slide, new SlideTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/admin/slides/{slide}",
     *  tags={"admin/slide"},
     *  summary="更新",
     *  description="不能用于排序",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="body",in="body",type="object",@SWG\Schema(ref="#/definitions/Slide")),
     *  @SWG\Response(response=201,description="",@SWG\Schema(ref="#/definitions/Slide")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(SlideRequest $request, Slide $slide)
    {
        $slide->fill($request->only(['title', 'image', 'link', 'description']));
        $slide->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/slides/{slide_id}",
     *  tags={"admin/slide"},
     *  summary="删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="slide_id",in="path",required=true,type="integer"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/admin/slides/sort",
     *  tags={"admin/slide"},
     *  summary="排序",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="body",in="body",@SWG\Schema(
     *      @SWG\Property(property="ids",type="array",description="排好顺序的ID集合，如[3,1,2]",@SWG\Items(type="integer"))
     *  )),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function sort(SlideRequest $request)
    {
        DB::transaction(function () {
            $ids = request('ids');
            foreach ($ids as $index => $id) {
                Slide::where('id', $id)->update(['seq' => $index + 1]);
            }
        });

        return $this->response->noContent();
    }
}
