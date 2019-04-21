<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\SlideRequest;
use App\Http\Transformers\SlideTransformer;
use Facades\App\Models\Setting;
use App\Models\Slide;
use DB;

class SlideController extends Controller
{

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->has('sort')) {
            request()->offsetSet('sort', 'seq,asc');
        }

        // 获取轮播图数据
        $slides = Slide::sorted()->get();

        // 域名
        $domain = fileDomain();

        // 返回页面
        return view('admin.slides.index', compact('slides', 'domain'));
    }


    /**
     * 创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $domain = fileDomain();

        return view('admin.slides.create', compact('domain'));
    }


    /**
     * 保存
     *
     * @param SlideRequest $request
     * @param Slide $slide
     * @return \Dingo\Api\Http\Response
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


//    public function show(Slide $slide)
//    {
//        return $this->response->item($slide, new SlideTransformer());
//    }


    /**
     * 修改
     *
     * @param Slide $slide
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Slide $slide)
    {
        $domain = fileDomain();

        return view('admin.slides.edit', compact('domain', 'slide'));
    }


    /**
     * 更新
     *
     * @param SlideRequest $request
     * @param Slide $slide
     * @return \Dingo\Api\Http\Response
     */
    public function update(SlideRequest $request, Slide $slide)
    {
        $slide->fill($request->only(['title', 'image', 'link', 'description']));
        $slide->save();

        return $this->response->noContent();
    }


    public function destroy(Slide $slide)
    {
        $slide->delete();

        return $this->response->noContent();
    }


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
