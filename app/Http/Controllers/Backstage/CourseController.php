<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\Status;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * 课程列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 获取学科书籍
        $courses = Course::filtered(array_filter($request->all()))
            ->sorted()
            ->withCount(['plans'])
            ->with(['user', 'default_plan'])
            ->paginate(self::perPage());

        // 课程统计信息
        $stat['total'] = Course::count();
        $stat['published'] = Course::published()->count();
        $stat['closed'] = Course::closed()->count();
        $stat['draft'] = Course::draft()->count();

        // 课程状态
        $status = ['draft' => '未发布', 'closed' => '已关闭', 'published' => '已发布'];

        return view('admin.courses.index', compact('courses', 'stat', 'status'));
    }


    /**
     * 推荐课程列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendIndex(Request $request)
    {
        $request->offsetSet('sort', 'recommended_seq,desc');

        // 获取学科书籍
        $courses = Course::filtered(array_filter($request->all()))
            ->sorted()
            ->where('is_recommended', true)
            ->withCount(['plans'])
            ->with(['user', 'default_plan'])
            ->paginate(self::perPage());

        // 课程统计信息
        $stat['total'] = Course::count();
        $stat['published'] = Course::published()->count();
        $stat['closed'] = Course::closed()->count();
        $stat['draft'] = Course::draft()->count();

        // 课程状态
        $status = ['draft' => '未发布', 'closed' => '已关闭', 'published' => '已发布'];

        return view('admin.courses.recommend_index', compact('courses', 'stat', 'status'));
    }

    /**
     * 删除课程
     *
     * @param Course $course
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function destroy(Course $course)
    {
        DB::transaction(function () use ($course) {
            $course->teachers()->delete();

            $course->delete();
        });


        return $this->response->noContent();
    }

    /**
     * 课程推荐
     *
     * @param Request $request
     * @param Course $course
     * @return \Dingo\Api\Http\Response
     */
    public function recommend(Request $request, Course $course)
    {
        // 数据验证
        $this->validate($request, [
            'is_recommended' => 'required|bool',
            'recommended_seq' => 'sometimes|integer|min:0|max:9999'
        ]);

        $course->is_recommended = $request->is_recommended;
        $course->recommended_seq = $request->recommended_seq ?? 0;
        $course->is_recommended ?
            $course->recommended_at = now() :
            $course->recommended_at = null;

        $course->save();

        return $this->response->noContent();
    }

    /**
     * 课程推荐页面
     *
     * @param Request $request
     * @param Course $course
     * @return \Dingo\Api\Http\Response
     */
    public function recommendShow(Request $request, Course $course)
    {
        return view('admin.courses.recommend', compact('course'));
    }


    /**
     * 课程发布
     *
     * @param Course $course
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function publish(Course $course, Request $request)
    {
        $this->validate($request, [
            'status' => 'required|in:published,closed'
        ]);

        // 当 发布 课程时，默认版本同时发布；当 关闭 时，仅关闭课程。
        $default_plan = $course->default_plan;

        if ($default_plan->tasks_count == 0 && $request->input('status') == 'published') {
            return $this->response->errorForbidden('默认版本无任何教学任务，不允许发布');
        }

        // 请求发布
        if ($request->status == Status::PUBLISHED) {
            // 开启事务
            DB::transaction(function () use ($course, $default_plan) {

                // 默认教学版本未发布
                if ($default_plan->status != Status::PUBLISHED) {
                    $default_plan->status = Status::PUBLISHED;
                    $default_plan->save();
                }

                $course->status = Status::PUBLISHED;
                $course->save();
            });
        } else {
            $course->status = Status::CLOSED;
            $course->save();
        }

        return $this->response->noContent();
    }
}