<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\PlanTeacherTransformer;
use App\Models\Plan;

class PlanTeacherController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/teachers",
     *  tags={"web/teacher"},
     *  summary="本版本教师列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherQuery-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherQuery-plan:title"),
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/PlanTeacherQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/PlanTeacher-include"),
     *  @SWG\Response(response=200,ref="#/responses/PlanTeacherResponse")
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->teachers()->filtered()->orderByDesc('seq')->get();

        return $this->response->collection($data, new PlanTeacherTransformer());
    }
}
