<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Course;
use App\Models\Plan;
use App\Models\Reply;
use App\Models\Task;
use App\Models\User;

class ReplyTransformer extends BaseTransformer
{
    public $availableIncludes = ['user', 'course', 'plan', 'task'];

    public $defaultIncludes = ['user'];

    public function transform(Reply $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'task_id' => $model->task_id,
            'is_elite' => $model->is_elite ? true : false,
            'content' => $model->content,
            'topic_id' => $model->topic_id,
            'status' => $model->status,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 用户
     */
    public function includeUser(Reply $model)
    {
        $user = $model->user()->select(User::$baseFields)->first();

        return $this->setDataOrItem($user, new UserTransformer());
    }

    /**
     * 课程
     */
    public function includeCourse(Reply $model)
    {
        $course = $model->course()->select(Course::$baseFields)->first();

        return $this->setDataOrItem($course, new CourseTransformer());
    }

    /**
     * 版本
     */
    public function includePlan(Reply $model)
    {
        $plan = $model->course()->select(Course::$baseFields)->first();

        return $this->setDataOrItem($plan, new PlanTransformer());
    }

    /**
     * 任务
     */
    public function includeTask(Reply $model)
    {
        $task = $model->task()->select(Task::$baseFields)->first();

        return $this->setDataOrItem($task, new TaskTransformer());
    }
}