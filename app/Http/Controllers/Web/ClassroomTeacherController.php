<?php

namespace App\Http\Controllers\Web;

use App\Http\Transformers\ClassroomTeacherTransformer;
use App\Models\Classroom;
use App\Models\ClassroomTeacher;
use App\Http\Controllers\Controller;

class ClassroomTeacherController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/classrooms/{classroom_id}/teachers",
     *  tags={"web/classroom"},
     *  summary="教师列表",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomTeacherQuery-type"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomTeacherQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomTeacherQuery-user:username"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/ClassroomTeacherPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Classroom $classroom)
    {
        $data = ClassroomTeacher::where('classroom_id', $classroom->id)->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ClassroomTeacherTransformer());
    }
}
