<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TestRequest;
use App\Http\Transformers\QuestionTransformer;
use App\Http\Transformers\TestTransformer;
use App\Models\Course;
use App\Models\PlanMember;
use App\Models\QuestionResult;
use App\Models\Task;
use App\Models\Test;
use App\Models\TestResult;

class TestController extends Controller
{
    /**
     * @SWG\Tag(name="web/test",description="考试")
     */

    /**
     * @SWG\GET(
     *  path="/manage/courses/{course_id}/tests",
     *  tags={"web/test"},
     *  summary="考试列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",required=true,type="integer",description="课程ID"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-total_score"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-questions_count"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-single_count"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-multiple_count"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-judge_count"),
     *  @SWG\Parameter(ref="#/parameters/TestQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/Test-sort"),
     *  @SWG\Parameter(ref="#/parameters/Test-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/TestPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Course $course)
    {
        $this->authorize('isCourseTeacher', $course);

        $data = $course->tests()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new TestTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/courses/{course_id}/tests",
     *  tags={"web/test"},
     *  summary="考试添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/TestForm-title"),
     *  @SWG\Parameter(ref="#/parameters/TestForm-course_id"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Test"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(TestRequest $request, Course $course, Test $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test->title = $request->title;
        $test->course_id = $course->id;
        $test->user_id = auth()->id();
        $test->save();

        return $this->response->item($test, new TestTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Get(
     *  path="/manage/courses/{course_id}/tests/{test_id}",
     *  tags={"web/test"},
     *  summary="考试详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程ID"),
     *  @SWG\Parameter(name="test_id",in="path",type="integer",required=true,description="考试ID"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Test"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Course $course, $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test = $course->tests()->findOrFail($test);

        return $this->response->item($test, new TestTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/manage/courses/{course_id}/tests/{test_id}",
     *  tags={"web/test"},
     *  summary="考试更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(name="test_id",in="path",type="integer",required=true,description="考试"),
     *  @SWG\Parameter(ref="#/parameters/TestForm-title"),
     *  @SWG\Parameter(ref="#/parameters/TestForm-course_id"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(TestRequest $request, Course $course, $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test = $course->tests()->findOrFail($test);

        $test->fill($request->all());
        $test->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/courses/{course_id}/tests/{test_id}",
     *  tags={"web/test"},
     *  summary="考试删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程ID"),
     *  @SWG\Parameter(name="test_id",in="path",type="integer",required=true,description="考试ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Course $course, $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test = $course->tests()->findOrFail($test);

        $test->delete();

        return $this->response->noContent();
    }
}
