<?php

namespace App\Observers;

use App\Enums\StudentStatus;
use App\Models\Classroom;
use App\Models\ClassroomMember;

class ClassroomMemberObserver
{
    /**
     * 监听学员加入
     *
     * @param Classroom $classroom
     */
    public function created(ClassroomMember $classroomMember)
    {
        $classroomMember->classroom()->increment('members_count');
    }

    /**
     * 监听学员移除
     *
     * @param Classroom $classroom
     */
    public function saved(ClassroomMember $classroomMember)
    {
        if ($classroomMember->isDirty('status') && $classroomMember->status === StudentStatus::EXITED) {
            $classroomMember->classroom()->decrement('members_count');
        }
    }
}