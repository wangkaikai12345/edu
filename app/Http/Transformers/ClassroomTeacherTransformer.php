<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\ClassroomTeacher;

class ClassroomTeacherTransformer extends BaseTransformer
{
    protected $availableIncludes = ['user', 'classroom'];

    public function transform(ClassroomTeacher $model)
    {
        return [
            'id' => $model->id,
            'classroom_id' => $model->classroom_id,
            'user_id' => $model->user_id,
            'type' => $model->type,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->created_at->toDateTimeString() : null,
        ];
    }

    public function includeUser(ClassroomTeacher $model)
    {
        return $this->item($model->user, new UserTransformer());
    }

    public function includeClassroom(ClassroomTeacher $model)
    {
        return $this->collection($model->classroom, new ClassroomTransformer());
    }
}