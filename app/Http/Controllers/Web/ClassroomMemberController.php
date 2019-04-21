<?php

namespace App\Http\Controllers\Web;

use App\Http\Transformers\ClassroomMemberTransformer;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Http\Controllers\Controller;

class ClassroomMemberController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/classrooms/{classroom_id}/members",
     *  tags={"web/classroom"},
     *  summary="成员列表",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-deadline"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-type"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-learned_count"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-learned_compulsory_count"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-finished_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-exited_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberQuery-last_learned_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMember-include"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMember-sort"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/UserPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Classroom $classroom)
    {
        $data = ClassroomMember::where('classroom_id', $classroom->id)->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ClassroomMemberTransformer());
    }
}
