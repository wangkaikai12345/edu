<?php

namespace App\Http\Controllers\Web;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PlanRequest;
use App\Http\Transformers\PlanTransformer;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanTeacher;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/courses/{course_id}/plans",
     *  tags={"web/plan"},
     *  summary="版本列表（普通）",
     *  description="已发布",
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(ref="#/parameters/Plan-sort"),
     *  @SWG\Parameter(ref="#/parameters/Plan-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/PlanResponse")
     * )
     */
    public function index(Course $course, PlanTeacher $teacher)
    {
        $plans = $course->plans()->published()->get();

        return $this->response->collection($plans, new PlanTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/courses/{course_id}/plans/{plan_id}",
     *  tags={"web/plan"},
     *  summary="版本详情（一般）",
     *  description="已发布",
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(ref="#/parameters/Plan-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Plan"),
     * )
     */
    public function show(Course $course, $plan, PlanTeacher $teacher)
    {
        $plan = $course->plans()->published()->findOrFail($plan);

        return $this->response->item($plan, new PlanTransformer());
    }
}
