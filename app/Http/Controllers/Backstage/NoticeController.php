<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NoticeRequest;
use App\Http\Transformers\NoticeTransformer;
use App\Models\Notice;

class NoticeController extends Controller
{
    /**
     * 公告通知
     *
     * @param Notice $notice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Notice $notice)
    {
        $notices = $notice->filtered()->sorted()->with('user')->paginate(self::perPage());

        return view('admin.notice.index', compact('notices'));
    }

    /**
     * 公告添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.notice.create');
    }


    /**
     * 保存
     *
     * @param NoticeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(NoticeRequest $request)
    {
        $notice = new Notice($request->all());
        $notice->user_id = auth()->id();
        $notice->save();

        return $this->response->item($notice, new NoticeTransformer())->setStatusCode(201);
    }


    public function edit(Notice $notice)
    {
        return view('admin.notice.edit', compact('notice'));
    }

    /**
     * 更新
     *
     * @param NoticeRequest $request
     * @param Notice $notice
     * @return \Dingo\Api\Http\Response
     */
    public function update(NoticeRequest $request, Notice $notice)
    {
        $notice->fill($request->all());
        $notice->save();

        return $this->response->noContent();
    }

    /**
     * 删除
     *
     * @param Notice $notice
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return $this->response->noContent();
    }
}