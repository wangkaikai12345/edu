<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PlanTeacherRequest;
use App\Http\Transformers\PlanTeacherTransformer;
use App\Models\Plan;
use App\Models\PlanTeacher;
use App\Models\User;

class PlanTeacherController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/plans/{plan_id}/teachers",
     *  tags={"web/teacher"},
     *  summary="版本教师列表（教师管理）",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/PlanTeacher-include"),
     *  @SWG\Response(response=200,ref="#/responses/PlanTeacherResponse")
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->teachers()->filtered()->orderByDesc('seq')->get();

        return $this->response->collection($data, new PlanTeacherTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/plans/{plan_id}/teachers",
     *  tags={"web/teacher"},
     *  summary="添加版本教师",
     *  description="",
     *  @SWG\Parameter(name="plan_id",in="path",required=true,type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="user_id",in="formData",type="integer",required=true),description="教师ID",
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherForm-seq"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/PlanTeacher"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Plan $plan, PlanTeacherRequest $request)
    {
        $this->authorize('isCourseCreator', $plan->course);

        // 判断是否拥有教师角色
        $user = User::role(UserType::TEACHER)->find($request->user_id);
        if (! $user) {
            abort(400, __('Only teacher can be added.'));
        }

        $teacher = new PlanTeacher();
        $teacher->user_id = $user->id;
        $teacher->course_id = $plan->course_id;
        $teacher->plan_id = $plan->id;
        $teacher->save();

        return $this->response->item($teacher, new PlanTeacherTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/plans/{plan_id}/teachers/{row_id}",
     *  tags={"web/teacher"},
     *  summary="版本教师排序",
     *  @SWG\Parameter(name="plan_id",in="path",required=true,type="integer",description="版本ID"),
     *  @SWG\Parameter(name="row_id",in="path",required=true,type="integer",description="本行记录ID"),
     *  @SWG\Parameter(ref="PlanTeacherForm-seq"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Plan $plan, $rowId, PlanTeacherRequest $request)
    {
        $teacherRow = PlanTeacher::findOrFail($rowId);

        $this->authorize('isCourseCreator', $plan->course);

        $teacherRow->seq = $request->seq;
        $teacherRow->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/plans/{plan_id}/teachers/{row_id}",
     *  tags={"web/teacher"},
     *  summary="版本教师删除",
     *  description="",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",description="版本ID",required=true),
     *  @SWG\Parameter(name="row_id",in="path",type="integer",description="本行记录ID",required=true),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Plan $plan, $rowId)
    {
        $this->authorize('isCourseCreator', $plan->course);

        $teacherRow = PlanTeacher::findOrFail($rowId);

        $teacherRow->delete();

        return $this->response->noContent();
    }
}
