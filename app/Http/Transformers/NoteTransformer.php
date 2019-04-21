<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Note;

class NoteTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'course', 'plan', 'task'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Note $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'task_id' => $model->task_id,
            'content' => $model->content,
            'status' => $model->status,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 记录人
     */
    public function includeUser(Note $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 版本
     */
    public function includePlan(Note $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 课程
     */
    public function includeCourse(Note $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 任务
     */
    public function includeTask(Note $model)
    {
        return $this->setDataOrItem($model->task, new TaskTransformer());
    }
}