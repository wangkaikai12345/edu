<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\User;

class PlanPolicy extends BasePolicy
{
    /**
     * 允许场景：该版本学员
     *
     * @param User $user
     * @param Plan $plan
     * @return int
     */
    public function isMember(User $user, Plan $plan)
    {
        $this->message(__('Only plan member can do this.'));

        $bool = PlanMember::where('user_id', $user->id)->where('plan_id', $plan->id)->count();

        return $this->trueOrThrow($bool);
    }

    /**
     * 允许场景：该版本教师
     *
     * @param User $user
     * @param Plan $plan
     * @return int
     */
    public function isPlanTeacher(User $user, Plan $plan)
    {
        $this->message(__('Only plan teacher can do this.'));

        $bool = PlanTeacher::where('user_id', $user->id)->where('plan_id', $plan->id)->count();

        return $this->trueOrThrow($bool);
    }
}
