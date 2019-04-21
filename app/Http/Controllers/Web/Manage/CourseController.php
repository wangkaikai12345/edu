<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CourseRequest;
use App\Http\Transformers\CourseTransformer;
use App\Models\Category;
use App\Models\Course;
use App\Models\PlanTeacher;
use App\Models\User;
use App\Models\Plan;
use DB;
use Dingo\Api\Http\Request;

class CourseController extends Controller
{
    /**
     * @SWG\Tag(name="web/course",description="课程")
     */

    /**
     * @SWG\Get(
     *  path="/manage/courses",
     *  tags={"web/course"},
     *  summary="课程列表（教师管理）",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-category_id"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-category:name"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-serialize_mode"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-is_recommended"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-recommended_seq"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-recommended_at"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-min_course_price"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-max_course_price"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-reviews_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-rating"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-notes_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-students_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-hit_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-materials_count"),
     *  @SWG\Parameter(ref="#/parameters/Course-sort"),
     *  @SWG\Parameter(ref="#/parameters/Course-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/CoursePagination"),
     * )
     */
    public function index(Request $request)
    {
        $courses = PlanTeacher::where('user_id', auth()->id())->pluck('course_id')->unique();

        $data = Course::whereIn('id', $courses->toArray())->notCopy()->filtered()->sorted()->paginate(self::perPage());

        // $data = Course::notCopy()->leftJoin('plan_teachers', function ($join) {
        //     $join->on('courses.id', '=', 'plan_teachers.course_id')->where('plan_teachers.user_id', '=', auth()->id());
        // })->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new CourseTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/manage/courses/{course_id}",
     *  tags={"web/course"},
     *  summary="课程详情",
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true,description="课程ID"),
     *  @SWG\Parameter(ref="#/parameters/Course-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Course"),
     *  @SWG\Response(response=403,description="请求被拒绝，未发布课程不做展示"),
     *  @SWG\Response(response=404,description="未找到课程"),
     * )
     */
    public function show(Course $course)
    {
        return $this->response->item($course, new CourseTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/courses",
     *  tags={"web/course"},
     *  summary="课程添加",
     *  description="附加产生课程默认版本",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/CourseForm-title"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-subtitle"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-summary"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-category_id"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-goals"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-audiences"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-cover"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-serialize_mode"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Course"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(CourseRequest $request)
    {
        $this->authorize('isTeacher', User::class);

        // 在创建时，不触发事件监听器
        Plan::unsetEventDispatcher();

        $course = DB::transaction(function () use ($request) {
            $course = new Course($request->all());
            $course->user_id = auth()->id();
            $course->save();

            $plan = new Plan(['title' => '默认版本']);
            $plan->course_id = $course->id;
            $plan->course_title = $course->title;
            $plan->user_id = auth()->id();
            $plan->is_default = true;
            $plan->save();

            $course->default_plan_id = $plan->id;
            $course->save();

            $teacher = new PlanTeacher();
            $teacher->user_id = auth()->id();
            $teacher->course_id = $course->id;
            $teacher->plan_id = $plan->id;
            $teacher->save();

            return $course;
        });

        return $this->response->item($course, (new CourseTransformer())->setDefaultIncludes(['default_plan']))->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/courses/{course_id}",
     *  tags={"web/course"},
     *  summary="课程修改",
     *  @SWG\Parameter(in="path",name="course_id",type="integer",description="课程 ID",required=true),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-title"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-subtitle"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-summary"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-category_id"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-goals"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-audiences"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-cover"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-serialize_mode"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-tags"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Course $course, CourseRequest $request)
    {
        $this->authorize('isCourseTeacher', $course);

        $courseData = array_only($request->all(), ['title', 'subtitle', 'summary', 'category_id', 'goals', 'audiences', 'cover', 'serialize_mode']);

        if ($request->category_id) {
            // 查询一级父类ID
            $courseData['category_first_level_id'] = Category::find($request->category_id)->parent_id;
        }

        $tags = $request->input('tags');

        DB::transaction(function () use ($course, $courseData, $tags) {
            $courseData &&  $course->update($courseData);
            $tags && $course->tags()->sync($tags);
        });

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/courses/{course_id}",
     *  tags={"web/course"},
     *  summary="课程删除",
     *  @SWG\Parameter(in="path",name="course_id",type="integer",description="课程 ID",required=true),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Course $course)
    {
        $this->authorize('isCreator', $course);

        $course->delete();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/manage/courses/{course_id}/publish",
     *  tags={"web/course"},
     *  summary="课程发布/取消",
     *  description="发布课程时，当默认版本未发布时，自动发布；关闭课程时，已发布版本不受不影响。",
     *  @SWG\Parameter(in="path",name="course_id",type="integer",description="课程 ID",required=true),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-status"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function publish(Course $course, CourseRequest $request)
    {
        $this->authorize('isCourseTeacher', $course);

        $course->status = $request->status;

        if ($course->isDirty('status')) {
            // 发布课程同时，会自动发布默认教学版本
            if ($course->status === Status::PUBLISHED) {
                DB::transaction(function () use ($course) {
                    $course->default_plan->status = Status::PUBLISHED;
                    $course->default_plan->save();

                    $course->save();
                });
            }// 取消发布，会自动关闭默认教学版本
            else {
                DB::transaction(function () use ($course) {
                    $course->default_plan->status = Status::CLOSED;
                    $course->default_plan->save();

                    $course->save();
                });
            }
        }

        return $this->response->noContent();
    }
}