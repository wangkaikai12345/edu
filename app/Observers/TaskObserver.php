<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * 监听用户更新事件
     *
     * @param Task $task
     * @return void
     */
    public function saving(Task $task)
    {
        if (!$task->isDirty('status')) {
            return;
        }

        if ($task->status === \App\Enums\Status::PUBLISHED) {
            // 选修
            !$task->is_optional && $task->plan()->increment('compulsory_tasks_count');
            $task->plan->increment('tasks_count');
        } else {
            // 选修
            !$task->is_optional && $task->plan()->decrement('compulsory_tasks_count');
            $task->plan->decrement('tasks_count');
        }
    }
}
