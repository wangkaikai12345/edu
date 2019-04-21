<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PlanRequest;
use App\Http\Transformers\PlanTransformer;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanTeacher;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * @SWG\Tag(name="web/plan",description="版本")
     */

    /**
     * @SWG\Get(
     *  path="/manage/courses/{course_id}/plans",
     *  tags={"web/plan"},
     *  summary="版本列表（教师管理）",
     *  description="",
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(ref="#/parameters/Plan-sort"),
     *  @SWG\Parameter(ref="#/parameters/Plan-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/PlanResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Course $course)
    {
        $this->authorize('isCourseTeacher', $course);

        $data = $course->plans()->sorted()->get();

        return $this->response->collection($data, new PlanTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/manage/courses/{course_id}/plans/{plan_id}",
     *  tags={"web/plan"},
     *  summary="版本详情",
     *  description="",
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(ref="#/parameters/Plan-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Plan"),
     *  @SWG\Response(response=403,description="请求被拒绝，缺少相关权限"),
     * )
     */
    public function show(Course $course, $plan, PlanTeacher $teacher)
    {
        $this->authorize('isCourseTeacher', $course);

        $plan = $course->plans()->findOrFail($plan);

        return $this->response->item($plan, new PlanTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/courses/{course_id}/plans",
     *  tags={"web/plan"},
     *  summary="版本添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-title"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-about"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-learn_mode"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_mode"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_started_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_days"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-goals"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-audiences"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-max_students_count"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-is_free"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-free_started_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-free_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-services"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-show_services"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-enable_finish"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-price"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-coin_price"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-serialize_mode"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-deadline_notification"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-notify_before_days_of_deadline"),
     *  @SWG\Parameter(ref="#/parameters/Plan-include"),
     *  @SWG\Response(response=201,ref="#/definitions/Plan"),
     *  @SWG\Response(response=403,description="请求被拒绝，缺少相关权限"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Course $course, PlanRequest $request)
    {
        $this->authorize('isCourseTeacher', $course);

        $plan = new Plan($request->all());
        $plan->course_id = $course->id;
        $plan->is_default = false;
        $plan->user_id = auth()->id();
        $plan->save();

        return $this->response->item($plan, new PlanTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/courses/{course_id}/plans/{plan_id}",
     *  tags={"web/plan"},
     *  summary="修改/更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-title"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-about"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-learn_mode"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_mode"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_started_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-expiry_days"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-goals"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-audiences"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-max_students_count"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-is_free"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-free_started_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-free_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-services"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-show_services"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-enable_finish"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-price"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-coin_price"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-serialize_mode"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-deadline_notification"),
     *  @SWG\Parameter(ref="#/parameters/PlanForm-notify_before_days_of_deadline"),
     *  @SWG\Response(response=204,description=""),
     *  @SWG\Response(response=403,description="请求被拒绝，缺少相关权限"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Course $course, $plan, PlanRequest $request)
    {
        $plan = $course->plans()->findOrFail($plan);

        $this->authorize('isPlanTeacher', $plan);

        if (!$request->price && !$request->coin_price) {
            $request->offsetSet('is_free', true);
            $request->offsetSet('price', 0);
            $request->offsetSet('coin_price', 0);
        }

        if ($request->coin_price) {
            $request->price = 0;
        }

        if ($request->price) {
            $request->coin_price = 0;
        }

        if ($request->expiry_mode == 'forever') {
            $request->offsetSet('expiry_started_at', null);
            $request->offsetSet('expiry_ended_at', null);
        }

	    $plan->fill($request->all());
        $plan->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/courses/{course_id}/plans/{plan_id}",
     *  tags={"web/plan"},
     *  summary="删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本"),
     *  @SWG\Response(response=204,description=""),
     *  @SWG\Response(response=403,description="请求被拒绝，缺少相关权限"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Course $course, $plan)
    {
        $plan = $course->plans()->findOrFail($plan);

        $this->authorize('isPlanTeacher', $plan);

        $plan->delete();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/courses/{course_id}/plans/{plan_id}/lock",
     *  tags={"web/plan"},
     *  summary="锁定（只能创建人操作，锁定后无法再进行修改，只有解锁后才能修改。）",
     *  description="当前状态的取反，比如当前是锁定状态则会解除锁定，当前状态是解锁状态，则会锁定。",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本"),
     *  @SWG\Response(response=204,description=""),
     *  @SWG\Response(response=403,description="请求被拒绝，缺少相关权限"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function lock(Course $course, $plan)
    {
        $plan = $course->plans()->findOrFail($plan);

        $this->authorize('isPlanTeacher', $plan);

        $plan->locked = !$plan->locked;
        $plan->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/manage/courses/{course_id}/plans/{plan_id}/publish",
     *  tags={"web/plan"},
     *  summary="发布/取消",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本"),
     *  @SWG\Parameter(name="type",type="string",enum={"draft","published","closed"},in="formData",required=true,description="发布:true/取消:false"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function publish(Course $course, $plan, Request $request)
    {
        $this->validate($request, ['type' => 'required|in:published,closed']);

        $plan = $course->plans()->findOrFail($plan);

        $this->authorize('isPlanTeacher', $plan);

        $plan->status = $request->type;
        $plan->save();

        return $this->response->noContent();
    }
}
