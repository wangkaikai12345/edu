<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    /**
     * 允许情景：是否为教师
     *
     * @param User $user
     * @return bool
     */
    public function isTeacher(User $user)
    {
        $this->message(__('Only teacher role can do this.'));

        return $this->trueOrThrow($user->isTeacher());
    }
}
