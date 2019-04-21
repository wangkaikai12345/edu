<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\SlideTransformer;
use App\Models\Slide;

class SlideController extends Controller
{
    // 标签
    /**
     * @SWG\Tag(name="web/slide",description="前台轮播图")
     */

    /**
     * @SWG\Get(
     *  path="/slides",
     *  tags={"web/slide"},
     *  summary="轮播图列表",
     *  description="默认以 seq 的升序排序",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="include",in="path",required=true,in="query",type="string",description="是否包含关联数据：user"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/SlideResponse")
     * )
     */
    public function index(Slide $slide)
    {
        self::orderBy('seq,asc');

        $data = $slide->filtered()->sorted()->get();

        return $this->response->collection($data, new SlideTransformer());
    }
}
