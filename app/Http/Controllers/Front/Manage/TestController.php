<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\TestRequest;
use App\Models\Course;
use App\Models\PlanMember;
use App\Models\QuestionResult;
use App\Models\Task;
use App\Models\Test;
use App\Models\TestResult;

class TestController extends Controller
{

    public function index(Course $course)
    {
        $this->authorize('isCourseTeacher', $course);

        $data = $course->tests()->filtered()->sorted()->paginate(self::perPage());

    }

    public function store(TestRequest $request, Course $course, Test $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test->title = $request->title;
        $test->course_id = $course->id;
        $test->user_id = auth('web')->id();
        $test->save();

    }

    public function show(Course $course, $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test = $course->tests()->findOrFail($test);

    }

    public function update(TestRequest $request, Course $course, $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test = $course->tests()->findOrFail($test);

        $test->fill($request->all());

        $test->save();

    }

    public function destroy(Course $course, $test)
    {
        $this->authorize('isCourseTeacher', $course);

        $test = $course->tests()->findOrFail($test);

        $test->delete();

    }
}
