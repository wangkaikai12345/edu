<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Transformers\ClassroomMemberTransformer;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomMemberController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/classrooms/{classroom_id}/members",
     *  tags={"web/classroom"},
     *  summary="班级成员列表",
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

    /**
     * @SWG\Post(
     *  path="/manage/classrooms/{classroom_id}/members",
     *  tags={"web/classroom"},
     *  summary="手动添加成员",
     *  description="仅班级教师允许",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberForm-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomMemberForm-remark"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/ClassroomMember"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request, Classroom $classroom)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        $this->validate($request, ['user_id' => 'required|exists:users,id', 'remark' => 'nullable|string|max:191']);
        $exists = ClassroomMember::where('classroom_id', $classroom->id)->where('user_id', $request->user_id)->exists();
        if ($exists) {
            $this->response->errorBadRequest(__('Repeat to add.'));
        }

        $member = new ClassroomMember($request->all());
        $member->classroom_id = $classroom->id;
        $member->save();

        return $this->response->item($member, new ClassroomMemberTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Delete(
     *  path="/manage/classrooms/{classroom_id}/members/{user_id}",
     *  tags={"web/classroom"},
     *  summary="移除学员",
     *  description="仅班级教师允许",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(name="user_id",type="integer",in="path",required=true,description="用户"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Classroom $classroom, $member)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        $member = ClassroomMember::where('classroom_id', $classroom->id)->where('user_id', $member)->firstOrFail();

        $member->delete();

        return $this->response->noContent();
    }
}
