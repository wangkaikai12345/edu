<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\StudentStatus;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;

class PlanTransformer extends BaseTransformer
{
    /**
     * @var array
     */
   protected $availableIncludes = ['course', 'chapters', 'user', 'members', 'teachers'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Plan $model)
    {
        return [
            'id' => $model->id,
            'course_id' => (int)$model->course_id,
            'course_title' => $model->course_title,
            'title' => $model->title,
            'about' => $model->about,
            'learn_mode' => $model->learn_mode,
            'expiry_mode' => $model->expiry_mode,
            'expiry_started_at' => $model->expiry_started_at ? $model->expiry_started_at->toDateTimeString() : null,
            'expiry_ended_at' => $model->expiry_ended_at ? $model->expiry_ended_at->toDateTimeString() : null,
            'expiry_days' => $model->expiry_days,
            'goals' => $model->goals,
            'audiences' => $model->audiences,
            'is_default' => $model->is_default,
            'max_students_count' => $model->max_students_count,
            'status' => $model->status,
            'user_id' => $model->user_id,
            'is_free' => $model->is_free,
            'free_started_at' => $model->free_started_at ? $model->free_started_at->toDateTimeString() : null,
            'free_ended_at' => $model->free_ended_at ? $model->free_ended_at->toDateTimeString() : null,
            'services' => $model->services,
            'show_services' => $model->show_services,
            'enable_finish' => $model->enable_finish,
            'income' => $model->income,
            'price' => $model->price,
            'origin_price' => $model->origin_price,
            'coin_price' => $model->coin_price,
            'origin_coin_price' => $model->origin_coin_price,
            'locked' => $model->locked,
            'buy' => $model->buy,
            'serialize_mode' => $model->serialize_mode,
            'max_discount' => $model->max_discount,
            'copy_id' => $model->copy_id,
            'deadline_notification' => $model->deadline_notification,
            'notify_before_days_of_deadline' => $model->notify_before_days_of_deadline,
            'is_bought' => $this->getIsBought($model),
            'is_control' => $this->getIsControl($model),
            'tasks_count' => $model->tasks_count,
            'compulsory_tasks_count' => $model->compulsory_tasks_count,
            'students_count' => $model->students_count,
            'notes_count' => $model->notes_count,
            'reviews_count' => $model->reviews_count,
            'rating' => $model->rating ?? 0.00,
            'hit_count' => $model->hit_count,
            'topics_count' => $model->topics_count,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 课程
     */
    public function includeCourse(Plan $model)
    {
        return $this->setDataOrItem($model->course, new CourseTransformer());
    }

    /**
     * 章节
     */
    public function includeChapters(Plan $model)
    {
        return $this->setDataOrItem($model->chapters, new ChapterTransformer());
    }

    /**
     * 用户
     */
    public function includeUser(Plan $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 成员
     */
    public function includeMembers(Plan $model)
    {
        $members = $model->members()->latest('created_at')->take(8)->get();

        return $this->setDataOrItem($members, new PlanMemberTransformer());
    }

    /**
     * 教师
     */
    public function includeTeachers(Plan $model)
    {
        return $this->setDataOrItem($model->teachers, new PlanTeacherTransformer());
    }

    /**
     * 是否已购买
     */
    private function getIsBought(Plan $model)
    {
        $id = auth()->id();
        if (!$id) { return false; }
        // 是否为版本成员
        if (PlanMember::where('user_id', $id)->where('plan_id', $model->id)->normal()->exists()) {
            return true;
        }
        // 是否为班级成员
        $classrooms = ClassroomMember::where('user_id', $id)->normal()->pluck('classroom_id');
        if ($classrooms->isEmpty()) {
            return false;
        }
        return ClassroomCourse::whereIn('classroom_id', $classrooms->toArray())->where('course_id', $model->course_id)->exists();
    }

    /**
     * 是否有管理权限
     */
    private function getIsControl(Plan $model)
    {
        if (!$me = auth()->user()) {
            return false;
        }

        if ($me->isAdmin() || $me->isSuperAdmin() || PlanTeacher::where('plan_id', $model->id)->where('user_id', $me->id)->count()) {
            return true;
        }

        return false;
    }
}