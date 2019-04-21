<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\TaskResult;

class TaskResultTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'plan', 'task'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(TaskResult $model)
    {
        return [
            'id' => $model->id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'task_id' => $model->task_id,
            'user_id' => $model->user_id,
            'status' => $model->status,
            'finished_at' => $model->finished_at ? $model->finished_at->toDateTimeString() : null,
            'time' => $model->time
        ];
    }

    /**
     * 用户
     */
    public function includeUser(TaskResult $model)
    {
        return $this->setDataOrItem($model->user, new MessageUserTransformer());
    }

    /**
     * 版本
     */
    public function includePlan(TaskResult $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 任务信息
     */
    public function includeTask(TaskResult $model)
    {
        return $this->setDataOrItem($model->task, new TaskTransformer());
    }
}