<?php

namespace App\Observers;

use App\Models\Chapter;
use App\Models\Plan;
use App\Models\PlanTeacher;

class PlanObserver
{
    /**
     * 将版本的创建人移入到版本教师之中
     */
    public function created(Plan $plan)
    {
        if (!PlanTeacher::where('plan_id', $plan->id)->where('user_id', $plan->user_id)->exists()) {
            $teacher = new PlanTeacher();
            $teacher->course_id = $plan->course_id;
            $teacher->plan_id = $plan->id;
            $teacher->user_id = $plan->user_id;
            $teacher->save();
        }

        // 默认自动创建阶段和关
        $chapter = new Chapter();
        $chapter->title = '默認第一階段';
        $chapter->course_id = $plan->course_id;
        $chapter->plan_id = $plan->id;
        $chapter->user_id = auth('web')->id();
        $chapter->parent_id = 0;
        $chapter->save();

        $chap = new Chapter();
        $chap->title = '默認第一関';
        $chap->course_id = $plan->course_id;
        $chap->plan_id = $plan->id;
        $chap->user_id = auth('web')->id();
        $chap->parent_id = $chapter->id;
        $chap->save();

    }

    /**
     * 更新
     *
     * @param Plan $plan
     */
    public function saved(Plan $plan)
    {
        // 更新课程最小价格、最大价格
        $count = Plan::where('course_id', $plan->course_id)->count();
        $course = $plan->course;
        $current = (int)$plan->price;

        // 如果课程仅有一个，那么则同时修改最大和最小价格
        if ($count === 1) {
            $course->min_course_price = $current;
            $course->max_course_price = $current;
            $course->save();
            return;
        }
        // 查询最大值、最小值
        $max = Plan::where('course_id', $plan->course_id)->max('price') ?? 0;
        $min = Plan::where('course_id', $plan->course_id)->min('price') ?? 0;

        $course->min_course_price = $min;
        $course->max_course_price = $max;
        $course->save();
        return;
    }

    /**
     * 删除
     *
     * @param Plan $plan
     */
    public function deleted(Plan $plan)
    {
        // 更新课程最小价格、最大价格
        $course = $plan->course;

        // 查询最大值、最小值
        $max = Plan::where('course_id', $plan->course_id)->max('price');
        $min = Plan::where('course_id', $plan->course_id)->min('price');
        $course->min_course_price = $min;
        $course->max_course_price = $max;
        $course->save();
    }
}