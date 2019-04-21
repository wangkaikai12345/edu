<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\User;

class PlanMemberTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['course', 'plan', 'user', 'order'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(PlanMember $model)
    {
        return [
            'id' => $model->id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'user_id' => $model->user_id,
            'order_id' => $model->order_id,
            'join_type' => $model->join_type,
            'deadline' => $model->deadline ? $model->deadline->toDateTimeString() : null,
            'learned_count' => (int)$model->learned_count,
            'learned_compulsory_count' => (int)$model->learned_compulsory_count,
            'notes_count' => (int)$model->notes_count,
            'note_last_updated_at' => $model->note_last_updated_at ? $model->note_last_updated_at->toDateTimeString() : null,
            'is_finished' => (bool)$model->is_finished,
            'finished_at' => $model->finished_at ? $model->finished_at->toDateTimeString() : null,
            'remark' => $model->remark,
            'status' => $model->status,
            'last_learned_at' => $model->last_learned_at ? $model->last_learned_at->toDateTimeString() : null,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 课程
     *
     * @param PlanMember $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeCourse(PlanMember $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 版本
     *
     * @param PlanMember $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includePlan(PlanMember $model)
    {
        return $this->setDataOrItem($model->plan, new PlanTransformer());
    }

    /**
     * 学员
     *
     * @param PlanMember $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeUser(PlanMember $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 订单
     *
     * @param PlanMember $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeOrder(PlanMember $model)
    {
        return $this->setDataOrItem($model->order, new OrderTransformer());
    }
}