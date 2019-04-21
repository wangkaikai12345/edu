<?php

namespace App\Http\Transformers;

use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanTeacher;
use App\Models\User;

class PlanTeacherTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    public $availableIncludes = ['user', 'plan', 'course'];

    /**
     * @param PlanTeacher $model
     * @return array
     */
    public $defaultIncludes = ['user'];

    public function transform(PlanTeacher $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'seq' => $model->seq,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 教师
     */
    public function includeUser(PlanTeacher $model)
    {
        $user = $model->user()->select(User::$baseFields)->first();

        return $this->setDataOrItem($user, new UserTransformer());
    }

    /**
     * 版本
     */
    public function includePlan(PlanTeacher $model)
    {
        $plan = $model->plan()->select(Plan::$baseFields)->first();

        return $this->setDataOrItem($plan, new PlanTransformer());
    }

    /**
     * 课程
     */
    public function includeCourse(PlanTeacher $model)
    {
        $course = $model->course()->select(Course::$baseFields)->first();

        return $this->setDataOrItem($course, new CourseTransformer());
    }
}