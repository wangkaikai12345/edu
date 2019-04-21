<?php

namespace App\Observers;

use App\Enums\TeacherType;
use App\Models\Classroom;
use App\Models\ClassroomTeacher;

class ClassroomObserver
{
    /**
     * 监听新新建课程
     *
     * @param Classroom $classroom
     */
    public function created(Classroom $classroom)
    {
        ClassroomTeacher::create([
            'classroom_id' => $classroom->id,
            'user_id' => $classroom->user_id,
            'type' => TeacherType::HEAD,
        ]);
    }
}