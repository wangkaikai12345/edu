<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\TeacherType;
use App\Enums\UserType;
use App\Http\Transformers\ClassroomTeacherTransformer;
use App\Models\Classroom;
use App\Models\ClassroomTeacher;
use App\Models\User;
use App\Rules\CustomEnumRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomTeacherController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/classrooms/{classroom_id}/teachers",
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

    /**
     * @SWG\Post(
     *  path="/manage/classrooms/{classroom_id}/teachers",
     *  tags={"web/classroom"},
     *  summary="手动添加教师",
     *  description="仅班级教师允许",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomTeacherForm-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomTeacherForm-type"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/ClassroomTeacher"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request, Classroom $classroom)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'type' => ['required', new CustomEnumRule(TeacherType::class)]
        ]);

        // 班级的班主任必须为教师角色、班级的教师必须为教师角色
        if ($request->type === TeacherType::HEAD || $request->type === TeacherType::TEACHER) {
            $user = User::role(UserType::TEACHER)->find($request->user_id);
            $user || abort(400, __('Only teacher can be added.'));
        }
        // 助教，允许为班级学员
        if ($request->type === TeacherType::ASSISTANT) {
            $user = $classroom->members()->where('user_id', $request->user_id)->first();
            $user || abort(400, __('Only classroom member can be added.'));
        }

        // 重复添加
        $exists = ClassroomTeacher::where('user_id', $request->user_id)->where('type', $request->type)->exists();
        if ($exists) {
            $this->response->errorBadRequest(__('Repeat to add.'));
        }

        $teacher = new ClassroomTeacher($request->all());
        $teacher->classroom_id = $classroom->id;
        $teacher->save();

        return $this->response->item($teacher, new ClassroomTeacherTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Delete(
     *  path="/manage/classrooms/{classroom_id}/teachers/{user_id}",
     *  tags={"web/classroom"},
     *  summary="移除教师",
     *  description="仅课程创建者允许",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(name="user_id",type="integer",in="path",required=true,description="用户"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Classroom $classroom, $teacher)
    {
        $this->authorize('isClassroomCreator', $classroom);

        $item = ClassroomTeacher::where('classroom_id', $classroom->id)->where('user_id', $teacher)->firstOrFail();

        $item->delete();

        return $this->response->noContent();
    }
}
