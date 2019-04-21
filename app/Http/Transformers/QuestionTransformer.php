<?php
/**
 * Created by PhpStorm.
 * Question: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Question;

class QuestionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'course', 'plan', 'chapter', 'task'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Question $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'type' => $model->type,
            'options' => $model->options,
            'answers' => $model->answers,
            'user_id' => $model->user_id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'chapter_id' => $model->chapter_id,
            'difficulty' => (integer)$model->difficulty,
            'explain' => $model->explain,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
            'pivot' => $model->pivot,
            'result' => $model->result
        ];
    }

    /**
     * 创建人
     */
    public function includeUser(Question $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 所属课程
     */
    public function includeCourse(Question $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 所属版本
     */
    public function includePlan(Question $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 所属章节
     */
    public function includeChapter(Question $model)
    {
        return $this->setDataOrItem($model->chapter, new ChaperTransformer());
    }

    /**
     * 所属任务
     */
    public function includeTask(Question $model)
    {
        return $this->setDataOrItem($model->task, new TaskTransformer());
    }
}