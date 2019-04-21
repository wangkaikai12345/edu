<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Chapter;
use League\Fractal\ParamBag;

class ChapterTransformer extends BaseTransformer
{
    /**
     * @var array 可以在 include 中增加的额外参数
     */
    private $validParams = ['status'];

    /**
     * @var array
     */
    public $availableIncludes = ['course', 'plan', 'user', 'tasks', 'children'];

    public function transform(Chapter $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'seq' => $model->seq,
            'plan_id' => $model->plan_id,
            'parent_id' => $model->parent_id,
            'user_id' => $model->user_id,
        ];
    }

    /**
     * 课程
     */
    public function includeCourse(Chapter $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 教学版本
     */
    public function includePlan(Chapter $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 创建人
     */
    public function includeUser(Chapter $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }
    /**
     * 包含子集
     */
    public function includeChildren(Chapter $model)
    {
        $children = $model->children()->orderBy('seq', 'asc')->get();

        return $this->setDataOrItem($children, new ChapterTransformer());
    }

    /**
     * 任务
     */
    public function includeTasks(Chapter $model, ParamBag $params)
    {
        if ($params->offsetGet('status') === null) {
            $tasks = $model->tasks()->orderBy('seq', 'asc')->get();
            return $this->setDataOrItem($tasks, new TaskTransformer());
        }

        list ($status) = $params->get('status');

        $data = $model->tasks()->where('status', $status)->orderBy('seq', 'asc')->get();

        return $this->setDataOrItem($data, new TaskTransformer());
    }
}