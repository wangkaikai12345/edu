<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\ClassroomCourse;

class ClassroomCourseTransformer extends BaseTransformer
{
    protected $availableIncludes = ['classroom', 'course', 'plan'];

    public function transform(ClassroomCourse $model)
    {
        return [
            'id' => $model->id,
            'classroom_id' => $model->classroom_id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'seq' => $model->seq,
            'is_synced' => $model->is_synced,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->created_at->toDateTimeString() : null,
        ];
    }

    /**
     * 子集
     *
     * @param ClassroomCourse $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeClassroom(ClassroomCourse $model)
    {
        return $this->collection($model->classroom, new ClassroomTransformer());
    }

    /**
     * 课程
     *
     * @param ClassroomCourse $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeCourse(ClassroomCourse $model)
    {
        return $this->item($model->course, new CourseTransformer());
    }

    /**
     * 版本
     *
     * @param ClassroomCourse $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includePlan(ClassroomCourse $model)
    {
        return $this->item($model->plan, new PlanTransformer());
    }
}