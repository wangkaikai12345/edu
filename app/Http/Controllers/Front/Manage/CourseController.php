<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\CourseRequest;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanTeacher;
use App\Models\Tag;
use App\Models\TagGroup;
use DB;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // 课程创建的页面
    public function create()
    {
        dd('课程创建');
    }

    /**
     * 创建课程
     */
    public function store(CourseRequest $request)
    {
        // 在创建时，不触发事件监听器
        Plan::unsetEventDispatcher();

        DB::transaction(function () use ($request) {
            // 创建课程
            $course = new Course($request->only('title'));
            $course->user_id = auth('web')->id();
            $course->save();

            // 创建课程的默认版本
            $plan = new Plan(['title' => '第一版本']);
            $plan->course_id = $course->id;
            $plan->course_title = $course->title;
            $plan->user_id = auth('web')->id();
            $plan->is_default = true;
            $plan->save();

            $course->default_plan_id = $plan->id;
            $course->save();

            // 版本教师的创建
            $teacher = new PlanTeacher();
            $teacher->user_id = auth('web')->id();
            $teacher->course_id = $course->id;
            $teacher->plan_id = $plan->id;
            $teacher->save();

            return $course;
        });

        return ajax('200', '课程创建成功!');
    }

    /**
     * 课程基本信息
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function edit(Course $course)
    {
        if (! $course->isControl()) abort(404);

        // 查询课程分类
        $categoryGroup = CategoryGroup::where('name', 'course')->first();
        $category = $categoryGroup ? Category::where('category_group_id', $categoryGroup->id)->get() : [];

        $labels = Tag::select('id', 'name as text')
            ->where(function ($q){
            return $q->where('tag_group_id', TagGroup::where('name', 'course')->first()->id);
        })->get()->toArray();

        $labels = json_encode($labels);

        return view('teacher.course.information', compact('course', 'category', 'labels'));
    }

    /**
     * 课程详细信息
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function detail(Course $course)
    {
        if (! $course->isControl()) abort(404);

        return frontend_view('manage.course.detail', compact('course'));
    }

    /**
     * 课程信息更新
     *
     * @param CourseRequest $request
     * @param Course $course
     * @return mixed
     * @author 王凯
     */
    public function update(CourseRequest $request, Course $course)
    {
        if (! $course->isControl()) abort(404);

        $courseData = array_only($request->all(), [
            'title', 'subtitle', 'summary', 'category_id', 'cover', 'serialize_mode'
        ]);

        if ($request->category_id) {
            // 查询一级父类ID
            $courseData['category_first_level_id'] = Category::find($request->category_id)->parent_id;
        }

        $courseData['goals'] = $request->goals;
        $courseData['audiences'] = $request->audiences;

        $tags = $request->input('labels');

        DB::transaction(function () use ($course, $courseData, $tags) {
            // 更新课程信息
            $courseData &&  $course->update($courseData);

            // 更新标签关联
            if ($tags) {
                $course->tags()->sync($tags);
            } else {
                $course->tags()->sync([]);
            }
        });

       return back()->withSuccess('更新成功');

    }

    /**
     * 发布和关闭课程
     *
     * @param Course $course
     * @param CourseRequest $request
     * @return $this
     * @author 王凯
     */
    public function publish(Course $course, CourseRequest $request)
    {
        if (! $course->isControl()) abort(404);

        $course->status = $request->status;

        if ($course->isDirty('status')) {
            // 发布课程同时
            if ($course->status == Status::PUBLISHED) {

                if (!$course->default_plan->tasks_count) {
                    return back()->withErrors('默认版本无任何教学任务，不允许发布');
                }

                // 会自动发布默认教学版本
                DB::transaction(function () use ($course) {

                    $course->default_plan->status = Status::PUBLISHED;
                    $course->default_plan->save();
                });
            }

            $course->save();
        }

        return back()->withSuccess('操作成功');

    }

    // 课程题库
    public function questions()
    {

    }

    // 课程考试
    public function tests()
    {

    }

    /**
     * 模糊搜索标签
     */
//    public function labelSearch(Request $request)
//    {
//
//        $data = [['id' => 1, 'text' => '大大的说法是']];
//        return ajax('200', 'ok', $data);
//    }
}