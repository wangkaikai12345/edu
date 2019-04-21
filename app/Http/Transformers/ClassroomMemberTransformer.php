<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\ClassroomMember;

class ClassroomMemberTransformer extends BaseTransformer
{
    protected $availableIncludes = ['user', 'classroom'];

    public function transform(ClassroomMember $model)
    {
        return [
            'classroom_id' => $model->classroom_id,
            'user_id' => $model->user_id,
            'remark' => $model->remark,
            'expired_at' => $model->expired_at ? $model->expired_at->toDateTimeString() : null,
            'type' => $model->type,
            'status' => $model->status,
            'learned_count' => $model->learned_count,
            'learned_compulsory_count' => $model->learned_compulsory_count,
            'finished_at' => $model->finished_at ? $model->finished_at->toDateTimeString() : null,
            'exited_at' => $model->exited_at ? $model->exited_at->toDateTimeString() : null,
            'last_learned_at' => $model->last_learned_at ? $model->last_learned_at->toDateTimeString() : null,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->created_at->toDateTimeString() : null,
        ];
    }

    public function includeUser(ClassroomMember $model)
    {
        return $this->item($model->user, new UserTransformer());
    }

    public function includeClassroom(ClassroomMember $model)
    {
        return $this->collection($model->classroom, new ClassroomTransformer());
    }
}