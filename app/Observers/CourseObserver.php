<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\Plan;

class CourseObserver
{
    /**
     * 更新
     *
     * @param Course $course
     */
    public function saved(Course $course)
    {
        // 当更新课程标题时，同时更新对应版本的标题
        if ($course->isDirty('title')) {
            Plan::where('course_id', $course->id)->update(['course_title' => $course->title]);
        }
    }
}