<?php
/**
 * Created by PhpStorm.
 * Test: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Test;

class TestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'course', 'tasks'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Test $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'user_id' => $model->user_id,
            'course_id' => $model->course_id,
            'total_score' => $model->total_score ?? 0,
            'questions_count' => $model->questions_count ?? 0,
            'single_count' => $model->single_count ?? 0,
            'multiple_count' => $model->multiple_count ?? 0,
            'judge_count' => $model->judge_count ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 创建人
     */
    public function includeUser(Test $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 所属课程
     */
    public function includeCourse(Test $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 所属任务
     */
    public function includeTasks(Test $model)
    {
        return $this->setDataOrItem($model->tasks, new TaskTransformer());
    }
}