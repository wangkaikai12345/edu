<?php

namespace App\Http\Controllers\Web;

use App\Enums\TaskTargetType;
use App\Enums\TaskResultStatus;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TaskResultTransformer;
use App\Models\TaskResult;
use Illuminate\Http\Request;

class TaskResultController extends Controller
{
    /**
     * @SWG\Tag(name="web/result",description="任务反馈")
     */

    /**
     * @SWG\Get(
     *  path="/tasks/{task}/result",
     *  tags={"web/result"},
     *  summary="详情",
     *  description="在进入播放页面之前，请求本接口，获取用户的观看时间",
     *  @SWG\Parameter(name="task_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(name="include",type="string",in="query",description="包含关联关系：user,plan,task"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/TaskResult"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Task $task)
    {
        $me = auth()->user();

        $result = $task->results()->where('user_id', $me->id)->first();
        if (!$result) {
            return $this->response->array([]);
        }

        return $this->response->item($result, new TaskResultTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/tasks/{task_id}/result",
     *  tags={"web/result"},
     *  summary="添加任务反馈（支持所有任务）",
     *  description="在播放期间 间隔 5 或 10 秒向后台发送本请求记录观看时间",
     *  @SWG\Parameter(name="task_id",type="integer",in="path",required=true),
     *  @SWG\Parameter(name="time",type="string",in="formData",description="单位：秒"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Task $task, Request $request)
    {
        $this->validate($request, ['time' => 'required|numeric']);

        $me = auth()->user();

        // 超管、管理、本版本教师则直接返回成功
        if ($me->isAdmin() || PlanTeacher::where('user_id', $me->id)->where('plan_id', $task->plan_id)->exists()) {
            return $this->response->noContent();
        }

        // 若为学员则查询用户是否在该教学版本之中
        $isPlanMember = PlanMember::where('user_id', $me->id)->where('plan_id', $task->plan_id)->exists();

        if (!$isPlanMember) {
            $classrooms = ClassroomMember::where('user_id', $me->id)->pluck('classroom_id');
            if ($classrooms->isEmpty()) {
                $isClassroomMember = false;
            } else {
                $isClassroomMember = ClassroomCourse::whereIn('classroom_id', $classrooms->toArray())->where('course_id', $task->course_id)->exists();
            }
        }
        if (!$isPlanMember && !$isClassroomMember) {
            $this->response->errorForbidden(__('Not a member of the course.'));
        }

        // 不同任务模式，执行不同的校验标准
        switch ($task->target_type) {
            case TaskTargetType::VIDEO:
            case TaskTargetType::AUDIO:
                return $this->video($task);
                break;
            case TaskTargetType::PPT:
            case TaskTargetType::DOC:
            case TaskTargetType::TEXT:
                return $this->ppt($task);
                break;
            case TaskTargetType::TEST:
                return $this->test($task);
                break;
        }
    }

    /**
     * 音频、视频类任务结果校验
     *
     * @param Task $task
     * @return \Dingo\Api\Http\Response
     */
    protected function video(Task $task): \Dingo\Api\Http\Response
    {
        $me = auth()->user();

        // 对传递的时间进行进 1 取整
        $time = (int)ceil(request()->time);

        // 不存在则创建
        $result = $task->results()->where('user_id', $me->id)->first();
        if (!$result) {
            $result = new TaskResult(request()->all());
            $result->course_id = $task->course_id;
            $result->plan_id = $task->plan_id;
            $result->task_id = $task->id;
            $result->user_id = $me->id;
            if ($time >= $task->length) {
                $result->status = TaskResultStatus::FINISH;
                $result->finished_at = now();
            }
            $result->save();
            return $this->response->noContent();
        }
        // 存在且为完成状态，不做操作
        if ($result->status == TaskResultStatus::FINISH) {
            return $this->response->noContent();
        }
        // 存在且为开始状态，执行更新
        if ($result->status == TaskResultStatus::START) {
            $result->time = $time;
            if ($time >= $task->length) {
                $result->status = TaskResultStatus::FINISH;
                $result->finished_at = now();
            }
            $result->save();
        }

        return $this->response->noContent();
    }

    /**
     * PPT、Doc、Text 任务结果校验
     *
     * @param Task $task
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    protected function ppt(Task $task): \Dingo\Api\Http\Response
    {
        $me = auth()->user();

        // 创建学习记录
        $result = new TaskResult(request()->all());
        $result->course_id = $task->course_id;
        $result->plan_id = $task->plan_id;
        $result->task_id = $task->id;
        $result->user_id = $me->id;
        $result->status = TaskResultStatus::FINISH;
        $result->finished_at = now();
        $result->save();

        return $this->response->noContent();
    }

    /**
     * PPT、Doc、Text 考试类任务结果校验
     *
     * @param Task $task
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    protected function test(Task $task): \Dingo\Api\Http\Response
    {
        $me = auth()->user();

        $test = $task->target;
        $testResult = $test->results()->where('user_id', $me->id)->first();

        // 不存在则创建
        $result = $task->results()->where('user_id', $me->id)->first();
        if (!$result) {
            // 创建学习记录
            $result = new TaskResult(request()->all());
            $result->course_id = $task->course_id;
            $result->plan_id = $task->plan_id;
            $result->task_id = $task->id;
            $result->user_id = $me->id;
            if ($testResult->is_finished) {
                $result->status = TaskResultStatus::FINISH;
                $result->finished_at = now();
            }
            $result->save();
        }
        // 存在且为完成状态，不做操作
        if ($result->status == TaskResultStatus::FINISH) {
            return $this->response->noContent();
        }
        // 存在且为开始状态，执行更新
        if ($result->status == TaskResultStatus::START) {
            $result->time = request()->time;
            if ($testResult->is_finished) {
                $result->status = TaskResultStatus::FINISH;
                $result->finished_at = now();
            }
            $result->save();
        }

        return $this->response->noContent();
    }
}
