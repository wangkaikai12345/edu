<?php

namespace App\Http\Controllers\Web;

use App\Http\Transformers\ClassroomCourseTransformer;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
use App\Models\ClassroomCourse;

class ClassroomCourseController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/classrooms/{classroom_id}/courses",
     *  tags={"web/classroom"},
     *  summary="列表",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseQuery-is_synced"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourse-include"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourse-sort"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/ClassroomCourseResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Classroom $classroom)
    {
        $data = ClassroomCourse::where('classroom_id', $classroom->id)->filtered()->sorted()->get();

        return $this->response->collection($data, new ClassroomCourseTransformer());
    }
}
