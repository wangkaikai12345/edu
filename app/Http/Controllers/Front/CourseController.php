<?php

namespace App\Http\Controllers\Front;

use App\Enums\Status;
use App\Events\CourseViewEvent;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\PlanTeacher;
use App\Models\User;
use App\Models\Plan;
use DB;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    /**
     * 课程首页
     *
     * @param Request $request
     * @param Course $course
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function index(Request $request, Course $course, Category $category)
    {
        $request->flash();

        // 课程数据
        $courses = $course->notCopy()
            ->where('status', Status::PUBLISHED)
            ->filtered()
            ->sorted()
            ->with('default_plan')
            ->paginate(config('theme.course_index_number'))
            ->appends($request->only(['sort', 'category_id', 'max_course_price', 'category_first_level_id']));

        // 分类数据(分类群组为1的课程分类)
        $categories = $category
            ->where(['category_group_id' => CategoryGroup::where('name', 'course')->value('id'), 'parent_id' => 0 ])
            ->with('children')
            ->get();

        return frontend_view('course.list', compact('courses', 'categories'));
    }

    /**
     * 课程详情
     *
     * @param $course
     * @return \Dingo\Api\Http\Response
     * @author 王凯
     */
    public function show(Course $course)
    {
//        ($course->status !== Status::PUBLISHED) && abort(404);

        // 默认版本信息
        $plan = $course->default_plan;

        // 章节目录
        $chapters = $plan->chapterChildren();

        // 笔记信息
        $notes = $plan->notes()->with('user')->latest()->take(config('theme.plan_detail'))->get();

        // 评价信息
        $reviews = $plan->reviews()->with('user')->latest()->take(config('theme.plan_detail'))->get();

        // 成员信息
        $members = $plan->members()->with('user')->sorted()->take(config('theme.plan_member'))->get();

        // 教师信息
        $teachers = $plan->teachers()->with('user')->latest()->get();

        // 公告信息
        $notices = $plan->notices()->onShow()->get();

        $course->increment('hit_count');

        return frontend_view('course.article', compact('course', 'plan', 'chapters', 'notes', 'reviews', 'members', 'teachers', 'notices'));
    }
}