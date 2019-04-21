<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\PlanTeacher;
use App\Models\User;

class CoursePolicy extends BasePolicy
{
    /**
     * 允许情景：是否为教师
     *
     * @param User $user
     * @return bool
     */
    public function isCourseTeacher(User $user, Course $course)
    {
        $this->message(__('Only course teacher can do this.'));

        $bool = PlanTeacher::where('user_id', $user->id)->where('course_id', $course->id)->count();

        return $this->trueOrThrow($bool);
    }

    /**
     * 允许情景：是否为创建人
     *
     * @param User $user
     * @return bool
     */
    public function isCourseCreator(User $user, Course $course)
    {
        $this->message(__('Only course creator can do this.'));

        return $this->trueOrThrow($user->id === $course->user_id);
    }

}
