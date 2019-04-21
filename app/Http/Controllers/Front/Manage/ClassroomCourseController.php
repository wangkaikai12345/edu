<?php

namespace App\Http\Controllers\Front\Manage;

use App\Models\Classroom;
use App\Models\ClassroomCourse;
use App\Models\Course;
use App\Models\PaperQuestion;
use App\Models\Plan;
use App\Models\PlanTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClassroomCourseController extends Controller
{
    /**
     * 班级课程管理首页
     */
    public function index(Request $request, Classroom $classroom, ClassroomCourse $classroomCourse)
    {
        $classroomCourses = $classroomCourse->where('classroom_id', $classroom->id)
            ->orderBy('seq')
            ->with(['course', 'plan'])
            ->get();
        return view('teacher.classroom.course.index', compact('classroom', 'classroomCourses'));
    }

    /**
     * 打开课程绑定页面 版本列表-只选择类型为通关式的版本
     */
    public function create(Request $request, Classroom $classroom)
    {
        $title = $request->title;
        $planTeachers = PlanTeacher::where(function ($query) {
            return $query->where('user_id', auth('web')->id())
                ->orWhere(function ($query) {
                    $cids = Course::where('user_id', auth('web')->id())->pluck('id');
                    return $query->orwhereIn('course_id', $cids);
                });
        })->when($title, function ($query) use ($title) {
            $cids = Course::where('title', 'like', '%' . $title . '%')->pluck('id');
            return $query->whereIn('course_id', $cids);
        })->where(function ($query) {
            $pids = Plan::where('learn_mode', 'lock')->pluck('id');
            return $query->whereIn('plan_id', $pids);
        })->where(function ($query) use ($classroom) {
            $hasPlans = ClassroomCourse::where('classroom_id', $classroom->id)->pluck('plan_id');
            return $query->whereNotIn('plan_id', $hasPlans);
        })
            ->groupBy('plan_id')
            ->with(['plan', 'course', 'user'])
            ->paginate(config('theme.my_course_num'));

        return view('teacher.classroom.course.list-modal', compact('planTeachers', 'classroom'));
    }

    /**
     * 保存班级选择的版本
     */
    public function store(Request $request, Classroom $classroom)
    {
        if (empty($request->plans)) return back()->withErrors('请勾选要添加的版本');
        // 查询已经存在的, 过滤掉
        $hasPlans = ClassroomCourse::where('classroom_id', $classroom->id)->pluck('plan_id')->toArray();

        $request->plans = array_diff($request->plans, $hasPlans);

        // 查询最大seq
        $maxSeq = ClassroomCourse::where('classroom_id', $classroom->id)->max('seq') ?? 0;

        // 查询所有的版本, 获取课程id
        $plans = Plan::whereIn('id', $request->plans)->get();
        $data = [];
        foreach ($plans as $plan) {
            $data[] = [
                'classroom_id' => $classroom->id,
                'course_id' => $plan->course_id,
                'plan_id' => $plan->id,
                'seq' => $maxSeq + 1
            ];
            $maxSeq++;
        }

        ClassroomCourse::query()->insert($data);

        $classroom->increment('courses_count', count($data));

        return back()->withSuccess('版本添加成功!');
    }

    /**
     *
     */
    public function show($id)
    {
        //
    }

    /**
     *
     */
    public function edit($id)
    {
        //
    }

    /**
     *
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 移除课程
     */
    public function destroy(ClassroomCourse $course)
    {
        $cid = $course->classroom_id;
        $seq = $course->seq;
        if ($course->delete()) {
            // 所有比这个seq大的, 全部减去1
            $courses = ClassroomCourse::where('classroom_id', $cid)->where('seq', '>', $seq)->get();
            $courses->each(function ($c) {
                $c->seq = $c->seq - 1;
                $c->save();
            });

            return ajax('200', '移除课程成功!');
        } else {
            return ajax('400', '移除课程失败!');
        }
    }

    /**
     * 取消课程同步
     */
    public function sync(ClassroomCourse $course)
    {
        try {
            DB::beginTransaction();
            // 复制课程
            $oldCourse = Course::find($course->course_id);
            $newCourse = $oldCourse->replicate();
            $newCourse->copy_id = $oldCourse->id;
            $newCourse->save();

            // 复制课程相关的标签
            $newCourse->tags()->attach($oldCourse->tags->pluck('id'));

            // 复制课程下的版本
            $oldPlan = $course->plan;
            $newPlan = $oldPlan->replicate();
            $newPlan->copy_id = $oldPlan->id;
            $newPlan->course_id = $newCourse->id;
            $newPlan->tasks_count = $oldPlan->tasks_count;
            $newPlan->save();

            // 复制版本下的章节
            $oldChapters = $oldPlan->chapters;

            // 优先复制父章节
            $oldChapterFs = $oldChapters->where('parent_id', 0);
            foreach ($oldChapterFs as $oldChapterF) {
                $newChapterF = $oldChapterF->replicate();
                $newChapterF->copy_id = $oldChapterF->id;
                $newChapterF->course_id = $newCourse->id;
                $newChapterF->plan_id = $newPlan->id;
                $newChapterF->save();

                // 复制子章节
                $oldChapterZs = $oldChapters->where('parent_id', $oldChapterF->id);
                foreach ($oldChapterZs as $oldChapterZ) {
                    $newChapterZ = $oldChapterZ->replicate();
                    $newChapterZ->copy_id = $oldChapterZ->id;
                    $newChapterZ->course_id = $newCourse->id;
                    $newChapterZ->plan_id = $newPlan->id;
                    $newChapterZ->parent_id = $newChapterF->id;
                    $newChapterZ->save();

                    // 复制章节下的任务
                    $oldTasks = $oldChapterZ->tasks;
                    foreach ($oldTasks as $oldTask) {
                        $newTask = $oldTask->replicate();
                        $newTask->copy_id = $oldTask->id;
                        $newTask->course_id = $newCourse->id;
                        $newTask->plan_id = $newPlan->id;
                        $newTask->chapter_id = $newChapterZ->id;

                        // 过滤掉视频和音频-使用原数据
                        if (!in_array($oldTask->target_type, ['video', 'audio', 'ppt', 'doc'])) {
                            // 复制任务下的资源
                            $oldTarget = $oldTask->target;
                            $newTarget = $oldTarget->replicate();
                            $newTarget->save();

                            // 如果是试卷, 复制问题和关联表
                            if ($oldTask->target_type == 'paper') {
                                // 复制所有问题
                                $oldQuestions = $oldTask->target->questions;
                                $oldPaperQuestions = $oldTask->target->paperQuestions->keyBy('question_id');
                                foreach ($oldQuestions as $oldQuestion) {
                                    // 新的题目
                                    $newQuestion = $oldQuestion->replicate();
                                    $newQuestion->save();

                                    // 复制问题相关的标签
                                    $newQuestion->tags()->attach($oldQuestion->tags->pluck('id'));

                                    // 新的试卷和问题关联关系
                                    $newPaperQuestion = $oldPaperQuestions[$oldQuestion->id]->replicate();
                                    $newPaperQuestion->paper_id = $newTarget->id;
                                    $newPaperQuestion->question_id = $newQuestion->id;
                                    $newPaperQuestion->save();
                                }

                                // 复制试卷相关的标签
                                $newTarget->tags()->attach($oldTarget->tags->pluck('id'));
                            }

                            $newTask->target_id = $newTarget->id;
                        }

                        $newTask->save();
                    }

                }

            }


            $course->is_synced = false;
            $course->course_id = $newCourse->id;
            $course->plan_id = $newPlan->id;
            $course->save();
            DB::commit();
            return ajax('200', '取消课程同步成功!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e);
        }
    }

    /**
     * 拖动排序课程
     */
    public function sort(Request $request, Classroom $classroom)
    {
        $ids = collect($request->ids);

        // 所有的要排序的内容
        $classroomCours = ClassroomCourse::where('classroom_id', $classroom->id)->get();
        $classroomCours->each(function ($c) use ($ids) {
            $c->seq = $ids->search($c->id) + 1;
            $c->save();
        });

        return ajax('200', '排序成功!');
    }
}
