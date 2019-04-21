<?php

namespace App\Observers;

use App\Enums\StudentStatus;
use App\Models\PlanMember;

class PlanMemberObserver
{
    /**
     * 加入版本
     *
     * @param PlanMember $planMember
     * @throws
     */
    public function created(PlanMember $planMember)
    {
        \DB::transaction(function () use ($planMember) {
            // 更新版本学员统计
            $planMember->plan()->increment('students_count');
            // 更新课程学员统计
            $planMember->plan->course()->increment('students_count');
        });
    }

    /**
     * 退出版本
     *
     * @param PlanMember $planMember
     * @throws
     */
    public function saved(PlanMember $planMember)
    {
        if ($planMember->isDirty('status') && $planMember === StudentStatus::EXITED) {
            \DB::transaction(function () use ($planMember) {
                // 更新版本学员统计
                $planMember->plan()->decrement('students_count');
                // 更新课程学员统计
                $planMember->plan->course()->decrement('students_count');
            });
        }
    }
}