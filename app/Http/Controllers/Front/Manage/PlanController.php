<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\PlanMemberRequest;
use App\Http\Requests\Front\PlanRequest;
use App\Http\Requests\Front\PlanTeacherRequest;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\Task;
use App\Models\User;
use App\Traits\JoinTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    use JoinTrait;

    // 课程版本列表
    public function index(Course $course)
    {
        return view('teacher.course.plan', compact('course'));
    }

    // 版本详情-阶段列表
    public function show(Course $course, Plan $plan)
    {
        $chapters = $plan->chapters->where('parent_id', 0)->sortBy('seq');
        return view('teacher.plan.plan_task', compact('course', 'plan', 'chapters'));
    }

    /**
     * 版本基本信息
     */
    public function edit(Course $course, Plan $plan)
    {
        return view('teacher.plan.setBasics', compact('course', 'plan'));
    }

    /**
     * 创建版本
     */
    public function store(Course $course, PlanRequest $request)
    {
        if (!$course->isControl()) abort(404);

        $plan = new Plan($request->all());
        $plan->course_id = $course->id;
        $plan->course_title = $course->title;
        $plan->is_default = false;
        $plan->learn_mode = $request->learn_mode;
        $plan->user_id = auth('web')->id();
        $plan->save();

        return ajax('200', '版本创建成功');
    }

    /**
     * 教学版本更新
     *
     * @param Course $course
     * @param Plan $plan
     * @param PlanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function update(Course $course, Plan $plan, PlanRequest $request)
    {
        !$plan->isControl() && abort(404);


        if ($request->is_free || (!$request->price && !$request->coin_price)) {

            $request->offsetSet('is_free', true);
            $request->offsetSet('price', 0);
            $request->offsetSet('coin_price', 0);
        } else {

            $request->offsetSet('is_free', false);
            if ($request->coin_price) {
                $request->price = 0;
            }
            if ($request->price) {
                $request->offsetSet('price', $request->price * 100);
            }

            // 判断原价是否空 如果为空 写入现价的值
            if (empty($request->origin_coin_price)) $request->offsetSet('origin_coin_price', $request->coin_price);
            if (empty($request->origin_price)) $request->offsetSet('origin_price', $request->price);

        }

        if ($request->expiry_mode == 'forever') {
            $request->offsetSet('expiry_started_at', null);
            $request->offsetSet('expiry_ended_at', null);
        }

        //

//	    $plan->fill(array_only($request->all(), ['title', 'locked', 'is_free', 'show_services', 'expiry_mode', 'enable_finish']));
        $plan->fill($request->all());
        $plan->save();

        return back()->with('success', '更新成功!');
    }

    /**
     * 发布教学版本
     */
    public function publish(Course $course, Plan $plan, Request $request)
    {
        $this->validate($request, ['status' => 'required|in:published,closed']);

        !$plan->isControl() && abort(404);

        $plan->status = $request->status;

        if ($plan->isDirty('status')) {
            // 发布版本同时
            if ($plan->status === Status::PUBLISHED) {

                // 版本下无任务，不允许发布
                if (!$plan->tasks_count) {
                    return ajax('400', '版本下无任何教学任务，不允许发布!');
                }

            }
            $plan->save();
        }

        return ajax('200', '教学版本修改成功!');
    }

    // 版本教师列表
    public function teachers(Course $course, Plan $plan)
    {
        // 查询所有教师角色的用户
        $teachers = User::role(UserType::TEACHER)->get();
        return view('teacher.plan.setTeacher', compact('course', 'plan', 'teachers'));
    }

    // 添加教师
    public function storeTeachers(Course $course, Plan $plan, PlanTeacherRequest $request)
    {
        !$course->isControl() && abort(404);

        // 判断是否拥有教师角色
        $user = User::role(UserType::TEACHER)->find($request->user_id);
        if (empty($user)) {
            return ajax('204', '用户不存在, 或者不是教师!');
        }

        // 查询是否该课程已经加过这个老师
        $check = PlanTeacher::where('plan_id', $plan->id)->where('user_id', $user->id)->count();
        if ($check > 0) return ajax('204', '不能重复添加!');

        $maxSeq = $plan->teachers()->max('seq') ?? 0;

        $teacher = new PlanTeacher();
        $teacher->user_id = $user->id;
        $teacher->course_id = $plan->course_id;
        $teacher->plan_id = $plan->id;
        $teacher->seq = $maxSeq;
        $teacher->save();

        $avatar = render_cover($user->avatar, 'avatar') ?? '/imgs/avatar.png';
        $url = route('manage.plans.teachers.delete', [$teacher->id]);
        return ajax('200', '设置教师成功!', compact('avatar', 'url'));
    }

    /**
     * 删除教师
     */
    public function deleteTeachers(PlanTeacher $teacher)
    {
        // 如果要删除的老师, 是版本创建者, 不让删除
        $plan = Plan::find($teacher->plan_id);

        if ($teacher->user_id == $plan->user_id) {
            return ajax('400', '不能删除课程的创建者!');
        }

        if ($teacher->delete()) {
            return ajax('200', '删除教师成功!');
        } else {
            return ajax('400', '删除教师失败!');
        }
    }

    /**
     * 教师排序
     */
    public function sortTeacher(Request $request)
    {
        $ids = collect($request->ids);

        $targets = PlanTeacher::whereIn('id', $ids)->get();

        $targets->each(function ($c) use ($ids) {
            $c->seq = $ids->search($c->id) + 1;
            $c->save();
        });

        return ajax('200', '排序成功!');
    }

    /**
     * 显示或者隐藏教师
     */
    public function teacherShow(PlanTeacher $teacher)
    {
        $teacher->is_show = $teacher->is_show == 0 ? 1 : 0;
        $teacher->save();
        return ajax('200', '设置成功!');
    }

    /**
     * 版本的订单
     *
     * @param Course $course
     * @param Plan $plan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function orders(Course $course, Plan $plan, Request $request)
    {
        !$plan->isControl() && abort(404);

        $start = $request->start_at;
        $end = $request->end_at;
        $status = $request->status;
        $payment = $request->payment;
        $trade = $request->trade_uuid;

        $orders = $plan->orders()->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->when($payment, function ($query) use ($payment) {
            return $query->where('payment', $payment);
        })->when($trade, function ($query) use ($trade) {
            return $query->where('trade_uuid', $trade);
        })->when($start, function ($query) use ($start) {
            return $query->where('created_at', '>', $start);
        })->when($end, function ($query) use ($end) {
            return $query->where('created_at', '<', $end);
        })->paginate(config('theme.teacher_num'));

        return view('teacher.plan.orderInquiry', compact('course', 'plan', 'orders'));
    }


    /**
     * 复制版本
     *
     * @param Course $course
     * @param Plan $plan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function copy(Course $course, Plan $plan, Request $request)
    {
        $this->validate($request, ['title' => 'required', Rule::unique('plans')->where('course_id', $course->id)]);

        !$plan->isControl() && abort(404);

        $title = $request->title;

        /**
         * 版本、章节、任务数据
         *
         * 迁移课程版本
         * 迁移课程版本章节
         * 迁移课程版本任务
         */

        // 阻止观察者事件
//        Plan::unsetEventDispatcher();
//        Task::unsetEventDispatcher();

        \DB::transaction(function () use ($course, $plan, $title) {

            // 创建版本
            $newPlan = $this->migratePlan($plan, $title);

            // 创建章
            $chapters = $plan->chapters()->where('parent_id', 0)->get();

            foreach ($chapters as $chapter) {
                $this->migrateChapter($chapter);

                // 创建节
                foreach ($chapter->children as $section) {
                    $this->migrateSection($section);

                    // 创建任务
                    foreach ($section->tasks as $task) {
                        $this->migrateTask($task);
                    }
                }
            }

        });

        return ajax('200', '版本复制成功');
    }

    /**
     * 复制课程版本
     *
     * @param Plan $plan
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migratePlan(Plan $plan, $title)
    {
        $newOne = $plan->replicate();
        $newOne->title = $title;
        $newOne->tasks_count = 0;
        $newOne->students_count = 0;
        $newOne->notes_count = 0;
        $newOne->reviews_count = 0;
        $newOne->topics_count = 0;
        $newOne->hit_count = 0;
        $newOne->rating = 0;
        $newOne->is_default = 0;
        $newOne->user_id = auth('web')->id();
        $newOne->copy_id = $plan->id;
        $newOne->save();

        return $newOne;
    }

    /**
     * 复制章节、任务及任务结果
     *
     * @param Chapter $chapter
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateChapter(Chapter $chapter)
    {
        // 迁移章
        $newChapter = $chapter->replicate();
        $newChapter->course_id = $chapter->course_id;
        $newChapter->plan_id = $chapter->plan_id;
        $newChapter->user_id = auth('web')->id();
        $newChapter->copy_id = $chapter->id;
        $newChapter->save();
        return $newChapter;
    }


    /**
     * @param Chapter $chapter
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateSection(Chapter $chapter)
    {
        // 迁移章
        $newChapter = $chapter->replicate();
        $newChapter->course_id = $chapter->course_id;
        $newChapter->plan_id = $chapter->plan_id;
        $newChapter->user_id = auth('web')->id();
        $newChapter->parent_id = $chapter->parent_id;
        $newChapter->copy_id = $chapter->id;
        $newChapter->save();
        return $newChapter;
    }

    /**
     * @param Task $task
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateTask(Task $task)
    {
        $newTask = $task->replicate();
        $newTask->course_id = $task->course_id;
        $newTask->plan_id = $task->plan_id;
        $newTask->chapter_id = $task->chapter_id;
        $newTask->user_id = auth('web')->id();
        $newTask->copy_id = $task->id;
        $newTask->save();

        return $newTask;
    }

}
