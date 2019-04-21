<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NoticeRequest;
use App\Http\Transformers\NoticeTransformer;
use App\Models\Notice;

class NoticeController extends Controller
{
    // 标签
    /**
     * @SWG\Tag(name="admin/notice",description="公告")
     */

    /**
     * @SWG\Get(
     *  path="/admin/notices",
     *  tags={"admin/notice"},
     *  summary="列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-started_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-ended_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-type"),
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/Notice-sort"),
     *  @SWG\Parameter(ref="#/parameters/Notice-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/NoticePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Notice $notice)
    {
        $notices = $notice->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($notices, new NoticeTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/notices/{notice_id}",
     *  tags={"admin/notice"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="notice_id",in="path",type="integer",description="公告ID"),
     *  @SWG\Parameter(ref="#/parameters/Notice-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Notice"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Notice $notice)
    {
        return $this->response->item($notice, new NoticeTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/notices",
     *  tags={"admin/notice"},
     *  summary="公告添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-content"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-type"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-started_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-ended_at"),
     *  @SWG\Response(response=201,description="pk",ref="#/definitions/Notice"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(NoticeRequest $request)
    {
        $notice = new Notice($request->all());
        $notice->user_id = auth()->id();
        $notice->save();

        return $this->response->item($notice, new NoticeTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/admin/notices/{notice_id}",
     *  tags={"admin/notice"},
     *  summary="公告更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="notice_id",in="path",type="integer",description="公告ID"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-content"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-type"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-started_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-ended_at"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(NoticeRequest $request, Notice $notice)
    {
        $notice->fill($request->all());
        $notice->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/notices/{notice_id}",
     *  tags={"admin/notice"},
     *  summary="公告删除",
     *  description="",
     *  @SWG\Parameter(name="notice_id",type="integer",in="path",required=true,description="公告ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return $this->response->noContent();
    }
}