<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\SettingType;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\ClassroomTeacher;
use Facades\App\Models\Setting;

class ClassroomTransformer extends BaseTransformer
{
    protected $availableIncludes = ['user', 'category', 'courses', 'plans', 'members', 'heads', 'teachers', 'assistants'];

    /**
     * @var string 域名
     */
    public $domain;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
    }

    public function transform(Classroom $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'description' => $model->description,
            'status' => $model->status,
            'expiry_mode' => $model->expiry_mode,
            'expiry_started_at' => $model->expiry_started_at ? $model->expiry_started_at->toDateTimeString() : null,
            'expiry_ended_at' => $model->expiry_ended_at ? $model->expiry_ended_at->toDateTimeString() : null,
            'expiry_days' => $model->expiry_days,
            'category_id' => $model->category_id,
            'origin_price' => $model->origin_price,
            'price' => $model->price,
            'cover' => $model->cover ? $this->domain . '/' . $model->cover : config('app.url') . '/images/cover.png',
            'domain' => $this->domain,
            'is_recommended' => (boolean)$model->is_recommended,
            'recommended_at' => $model->recommended_at ? $model->recommended_at->toDateTimeString() : null,
            'recommended_seq' => $model->recommended_seq,
            'members_count' => $model->members_count,
            'courses_count' => $model->courses_count,
            'user_id' => $model->user_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->created_at->toDateTimeString() : null,
            'is_bought' => $this->getIsBought($model),
            'is_control' => $this->getIsControl($model),
        ];
    }

    public function includeUser(Classroom $model)
    {
        return $this->item($model->user, new UserTransformer());
    }

    public function includeCategory(Classroom $model)
    {
        return $this->item($model->category, new CategoryTransformer());
    }

    public function includeCourses(Classroom $model)
    {
        return $this->collection($model->courses, new CourseTransformer());
    }

    public function includeMembers(Classroom $model)
    {
        return $this->collection($model->members, new UserTransformer());
    }

    public function includeHeads(Classroom $model)
    {
        return $this->collection($model->heads, new UserTransformer());
    }

    public function includeTeachers(Classroom $model)
    {
        return $this->collection($model->teachers, new UserTransformer());
    }

    public function includeAssistants(Classroom $model)
    {
        return $this->collection($model->assistants, new UserTransformer());
    }

    public function includePlans(Classroom $model)
    {
        return $this->collection($model->plans, new PlanTransformer());
    }

    /**
     * 是否已购买
     */
    private function getIsBought(Classroom $model)
    {
        $id = auth()->id();
        if (!$id) { return false; }

        // 是否为班级成员
        $classrooms = ClassroomMember::where('user_id', $id)->normal()->pluck('classroom_id');
        if ($classrooms->isEmpty()) {
            return false;
        }

        return true;
    }

    /**
     * 是否有管理权限
     */
    private function getIsControl(Classroom $model)
    {
        if (!$me = auth()->user()) {
            return false;
        }

        if ($me->isAdmin() || $me->isSuperAdmin() || ClassroomTeacher::where('classroom_id', $model->id)->where('user_id', $me->id)->count()) {
            return true;
        }

        return false;
    }
}