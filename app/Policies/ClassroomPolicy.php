<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\ClassroomTeacher;
use App\Models\User;

class ClassroomPolicy extends BasePolicy
{
    /**
     * 允许情景：是否为班级教师
     *
     * @param User $user
     * @return bool
     */
    public function isClassroomTeacher(User $user, Classroom $classroom)
    {
        $this->message(__('Only class teacher can do this.'));

        $bool = ClassroomTeacher::where('user_id', $user->id)->where('classroom_id', $classroom->id)->exists();

        return $this->trueOrThrow($bool);
    }

    /**
     * 允许情景：是否为创建人
     *
     * @param User $user
     * @return bool
     */
    public function isClassroomCreator(User $user, Classroom $classroom)
    {
        $this->message(__('Only class creator can do this.'));

        $bool = Classroom::where('user_id', $user->id)->where('classroom_id', $classroom->id)->exists();

        return $this->trueOrThrow($bool);
    }
}
