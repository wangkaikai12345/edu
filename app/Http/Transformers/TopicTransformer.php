<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Topic;

class TopicTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    public $availableIncludes = ['user', 'course', 'plan', 'task', 'replies', 'latest_replier'];

    public function transform(Topic $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'task_id' => $model->task_id,
            'type' => $model->type,
            'status' => $model->status,
            'is_stick' => $model->is_stick ?? false,
            'is_elite' => $model->is_elite ?? false,
            'is_audited' => $model->is_audited ?? false,
            'title' => $model->title,
            'content' => $model->content,
            'replies_count' => $model->replies_count ?? 0,
            'hit_count' => $model->hit_count ?? 0,
            'latest_replier_id' => $model->latest_replier_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 用户
     */
    public function includeUser(Topic $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 最新用户
     */
    public function includeLatestReplier(Topic $model)
    {
        return $this->setDataOrItem($model->latest_replier, new UserTransformer());
    }

    /**
     * 回复
     */
    public function includeReplies(Topic $model)
    {
        return $this->setDataOrItem($model->replies, new ReplyTransformer());
    }

    /**
     * 课程
     */
    public function includeCourse(Topic $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 教学版本
     */
    public function includePlan(Topic $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 任务
     */
    public function includeTask(Topic $model)
    {
        return $this->setDataOrItem($model->task, new TaskTransformer());
    }
}