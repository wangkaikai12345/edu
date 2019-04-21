<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\NoticeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PlanNoticeRequest;
use App\Http\Transformers\NoticeTransformer;
use App\Models\Notice;
use App\Models\Plan;

class PlanNoticeController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/plans/{plan_id}/notices",
     *  tags={"web/notice"},
     *  summary="版本公告列表",
     *  description="",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="版本ID"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/Notice-sort"),
     *  @SWG\Parameter(ref="#/parameters/Notice-include"),
     *  @SWG\Response(response=200,ref="#/responses/NoticePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Plan $plan)
    {
        $this->authorize('isPlanTeacher', $plan);

        $data = $plan->notices()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NoticeTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/plans/{plan_id}/notices",
     *  tags={"web/notice"},
     *  summary="版本公告添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="课程"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-content"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-started_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-ended_at"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Notice"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Plan $plan, PlanNoticeRequest $request)
    {
        $this->authorize('isPlanTeacher', $plan);

        $notice = new Notice($request->all());
        $notice->type = NoticeType::PLAN;
        $notice->user_id = auth()->id();
        $notice->plan_id = $plan->id;
        $notice->save();

        return $this->response->item($notice, new NoticeTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/plans/{plan_id}/notices/{notice_id}",
     *  tags={"web/notice"},
     *  summary="版本公告修改",
     *  description="",
     *  @SWG\Parameter(name="plan_id",in="path",required=true,type="integer",description="版本ID"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-content"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-started_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeForm-ended_at"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Plan $plan, $notice, PlanNoticeRequest $request)
    {
        $this->authorize('isPlanTeacher', $plan);

        $notice = $plan->notices()->findOrFail($notice);
        $notice->fill($request->all());
        $notice->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/plans/{plan_id}/notices/{notice_id}",
     *  tags={"web/notice"},
     *  summary="版本公告删除",
     *  description="",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="notice_id",in="path",type="integer",required=true,description="公告ID"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Plan $plan, $notice)
    {
        $this->authorize('isPlanTeacher', $plan);

        $notice = $plan->notices()->findOrFail($notice);
        $notice->delete();

        return $this->response->noContent();
    }
}
