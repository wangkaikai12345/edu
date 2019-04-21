<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\StudentStatus;
use App\Enums\TaskResultStatus;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\Task;
use App\Models\TaskResult;

/**
 * Class TaskTransformer
 * @package App\Http\Transformers
 */
class TaskTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['chapter', 'results', 'user', 'target'];

    public function transform(Task $model)
    {
        return [
            'id' => $model->id,
            'course_id' => $model->course_id,
            'plan_id' => $model->plan_id,
            'chapter_id' => $model->chapter_id,
            'title' => $model->title,
            'mode' => $model->mode,
            'type' => $model->type,
            'is_free' => (bool)$model->is_free,
            'is_optional' => (bool)$model->is_optional,
            'status' => $model->status,
            'user_id' => $model->user_id,
            'length' => $model->length,
            'seq' => $model->seq,
            'started_at' => $model->started_at ? $model->started_at->toDateTimeString() : null,
            'ended_at' => $model->ended_at ? $model->ended_at->toDateTimeString() : null,
            'learning_progress' => $this->getLearningProgress($model),
            'target_type' => $model->target_type,
            'target_id' => $model->target_id,
        ];
    }

    /**
     * 章节
     */
    public function includeChapter(Task $model)
    {
        return $this->setDataOrItem($model->chapter, new ChapterTransformer());
    }

    /**
     * 用户
     */
    public function includeUser(Task $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 任务完成结果
     */
    public function includeResults(Task $model)
    {
        return $this->setDataOrItem($model->results, new TaskResultTransformer());
    }

    /**
     * 任务详情
     */
    public function includeTarget(Task $model)
    {
        // TODO 可能这里存在问题（transformer 使用的问题而非逻辑问题）
        // 查询是否登录，并查询是否为版本成员 或 版本教师
        $me = auth()->user();

        // 多态关联处理 transformer 问题
        $modelName = ucfirst($model->target_type);
        $transformer = resolve("App\\Http\\Transformers\\{$modelName}Transformer");

        // 判断任务是否免费
        if ($model->is_free) {
            return $this->setDataOrItem($model->target, $transformer);
        }

        // 版本成员、版本教师、管理员、班级成员则返回数据
        if (!$me) {
            return $this->null();
        }
        if (PlanMember::where('plan_id', $model->plan_id)->where('user_id', $me->id)->where('status', '!=', StudentStatus::EXITED)->exists()) {
            return $this->setDataOrItem($model->target, $transformer);
        }

        if (PlanTeacher::where('plan_id', $model->plan_id)->where('user_id', $me->id)->exists()) {
            return $this->setDataOrItem($model->target, $transformer);
        }

        if ($me->isAdmin()) {
            return $this->setDataOrItem($model->target, $transformer);
        }

        // 是否为班级成员
        $classrooms = ClassroomMember::where('user_id', $me->id)->where('status', '!=', StudentStatus::EXITED)->pluck('classroom_id');
        if ($classrooms->isEmpty()) {
            return $this->null();
        }
        if (ClassroomCourse::whereIn('classroom_id', $classrooms->toArray())->where('course_id', $model->course_id)->exists()) {
            return $this->setDataOrItem($model->target, $transformer);
        }
        return $this->null();
    }

    /**
     * 用户学习状态
     */
    public function getLearningProgress(Task $model)
    {
        if (!$me = auth()->user()) {
            return false;
        }

        $result = TaskResult::where('user_id', $me->id)->where('task_id', $model->id)->first();

        if (!$result) {
            return false;
        } else if ($result->status === TaskResultStatus::FINISH) {
            return true;
        } else {
            return $result->time;
        }
    }
}