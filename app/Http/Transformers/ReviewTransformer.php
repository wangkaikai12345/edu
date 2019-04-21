<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Review;

class ReviewTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['plan', 'course'];

    /**
     * @var array
     */
    protected $defaultIncludes = ['user'];

    public function transform(Review $model)
    {
        return [
            'id' => $model->id,
            'content' => $model->content,
            'rating' => $model->rating,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 发表用户
     */
    public function includeUser(Review $model)
    {
        return $this->setDataOrItem($model->user, new MessageUserTransformer());
    }

    /**
     * 版本
     */
    public function includePlan(Review $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 课程
     */
    public function includeCourse(Review $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }
}