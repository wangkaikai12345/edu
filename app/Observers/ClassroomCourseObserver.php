<?php

namespace App\Observers;

use App\Models\Classroom;
use App\Models\ClassroomCourse;

class ClassroomCourseObserver
{
    /**
     * 监听新建课程
     *
     * @param Classroom $classroom
     */
    public function created(ClassroomCourse $classroomCourse)
    {
        $classroomCourse->classroom()->increment('courses_count');
    }

    /**
     * 监听修改课程
     *
     * @param Classroom $classroom
     */
    public function saved(ClassroomCourse $classroomCourse)
    {
        if ($classroomCourse->isDirty('plan_id')) {
            $classroomCourse->classroom()->decrement('courses_count');
        }
    }

    /**
     * 监听删除课程
     *
     * @param Classroom $classroom
     */
    public function deleted(ClassroomCourse $classroomCourse)
    {
        $classroomCourse->classroom()->decrement('courses_count');
    }
}