<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Requests\Web\ClassroomRequest;
use App\Http\Transformers\ClassroomTransformer;
use App\Models\Chapter;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\ClassroomTeacher;
use App\Models\Course;
use App\Models\Plan;
use App\Models\Task;
use App\Models\TaskResult;

class ClassroomController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/classrooms",
     *  tags={"web/classroom"},
     *  summary="班级列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-expiry_mode"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-expiry_started_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-expiry_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-category_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-origin_price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/Classroom-sort"),
     *  @SWG\Parameter(ref="#/parameters/Classroom-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/ClassroomPagination"),
     * )
     */
    public function index()
    {
        $data = Classroom::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ClassroomTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/manage/classrooms/{classroom_id}",
     *  tags={"web/classroom"},
     *  summary="班级详情",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",in="path",type="integer",required=true,description="班级ID"),
     *  @SWG\Parameter(ref="#/parameters/Classroom-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Classroom"),
     * )
     */
    public function show(Classroom $classroom)
    {
        return $this->response->item($classroom, new ClassroomTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/classrooms",
     *  tags={"web/classroom"},
     *  summary="班级添加",
     *  description="教师、管理允许添加",
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-title"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-description"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_mode"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_started_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_days"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-category_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-origin_price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-cover"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/ClassroomPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(ClassroomRequest $request)
    {
        $classroom          = new Classroom($request->all());
        $classroom->user_id = auth()->id();
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/classrooms/{classroom_id}",
     *  tags={"web/classroom"},
     *  summary="班级更新",
     *  description="允许教师管理",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-title"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_mode"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_started_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-expiry_days"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-category_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-origin_price"),
     *  @SWG\Response(response=204,description="ok",ref="#/responses/ClassroomPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        $classroom->fill($request->all());
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Delete(
     *  path="/manage/classrooms/{classroom_id}",
     *  tags={"web/classroom"},
     *  summary="班级删除",
     *  description="仅班级创建人",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Response(response=204,description="ok",ref="#/responses/ClassroomPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Classroom $classroom)
    {
        $this->authorize('isClassroomCreator', $classroom);
        /**
         * 1. 清除班级
         * 2. 删除关联教师
         * 3. 移除关联课程
         *  - 若为同步，无需管理额外数据
         *  - 若为不同步，则删除班级，同时移除班级版本、章节、任务以及任务记录等
         * 4. 移除班级成员
         *  - 学习记录
         */
        // 数据准备
        $classroomCourses  = ClassroomCourse::where('classroom_id', $classroom->id)->get();

        $classroomTeachers = ClassroomTeacher::where('classroom_id', $classroom->id)->get();
        \DB::transaction(function () use ($classroom, $classroomCourses, $classroomTeachers) {
            // 移除班级成员
            ClassroomMember::where('classroom_id', $classroom->id)->delete();
            // 移除关联课程
            foreach ($classroomCourses as $classroomCourse) {
                $this->removeCourse($classroomCourse);
            }
            // 移除关联教师
            ClassroomTeacher::where('classroom_id', $classroom->id)->delete();
            // 移除班级
            ClassroomCourse::where('classroom_id', $classroom->id)->delete();
        });

        $classroom->delete();

        return $this->response->noContent();
    }

    /**
     * 移除班级的课程
     *
     * @param $classroomCourse
     * @throws \Exception
     * @return bool
     */
    private function removeCourse($classroomCourse)
    {
        if ($classroomCourse->is_synced) {
            $classroomCourse->delete();
            return true;
        }
        // 复制的课程
        Course::where('id', $classroomCourse->course_id)->delete();
        // 复制的版本
        Plan::where('course_id', $classroomCourse->course_id)->delete();
        // 复制的章节
        Chapter::where('course_id', $classroomCourse->course_id)->delete();
        // 复制的任务
        Task::where('course_id', $classroomCourse->course_id)->delete();
        // 以及该课程的学习记录
        TaskResult::where('course_id', $classroomCourse->course_id)->delete();
        // 本条记录
        $classroomCourse->delete();
        return true;
    }

    /**
     * @SWG\Delete(
     *  path="/manage/classrooms/{classroom_id}/publish",
     *  tags={"web/classroom"},
     *  summary="发布/取消",
     *  description="已发布则取消，其它状态则发布",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Response(response=204,description="ok",ref="#/responses/ClassroomPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function publish(Classroom $classroom)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        $classroom->status === \App\Enums\Status::PUBLISHED
            ? $classroom->status = \App\Enums\Status::CLOSED
            : $classroom->status = \App\Enums\Status::PUBLISHED;
        $classroom->save();

        return $this->response->noContent();
    }
}
