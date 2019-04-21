<?php

namespace App\Http\Controllers\Web\Manage;

use App\Enums\TaskTargetType;
use App\Enums\TaskResultStatus;
use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TaskRequest;
use App\Http\Transformers\TaskTransformer;
use App\Models\Chapter;
use App\Models\PlanMember;
use App\Models\Task;
use App\Models\TaskResult;
use App\Models\Text;
use DB;

class TaskController extends Controller
{
    /**
     * @SWG\Tag(name="web/task",description="任务")
     */

    /**
     * @SWG\Get(
     *  path="/manage/chapters/{chapter_id}/tasks",
     *  tags={"web/task"},
     *  summary="任务列表",
     *  description="",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(ref="#/parameters/Task-sort"),
     *  @SWG\Parameter(ref="#/parameters/Task-include"),
     *  @SWG\Response(response=200,ref="#/responses/TaskResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Chapter $chapter)
    {
        $this->authorize('isPlanTeacher', $chapter->plan);

        $data = $chapter->tasks()->orderBy('seq')->filtered()->get();

        return $this->response->collection($data, new TaskTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/manage/chapters/{chapter_id}/tasks/{task_id}",
     *  tags={"web/task"},
     *  summary="任务详情",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(name="task_id",in="path",type="integer",required=true,description="任务ID"),
     *  @SWG\Parameter(ref="#/parameters/Task-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Task"),
     *  @SWG\Response(response=403,description="请求被拒绝"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Chapter $chapter, $task)
    {
        $this->authorize('isPlanTeacher', $chapter->plan);

        $task = $chapter->tasks()->findOrFail($task);

        return $this->response->item($task, new TaskTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/chapters/{chapter_id}/tasks",
     *  tags={"web/task"},
     *  summary="添加任务",
     *  description="视频、音频、PPT、DOC、图文、考试等",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",description="小节"),
     *  @SWG\Parameter(name="target_type",in="formData",required=true,type="string",enum={"video","audio","ppt","doc","text","test"},description="任务模式：视频、音频、PPT、Doc、图文、考试"),
     *  @SWG\Parameter(name="type",in="formData",required=true,type="string",enum={"preparation","task","exercise","homework","extra"},description="任务类型：预习、任务、联系、作业、课外"),
     *  @SWG\Parameter(name="title",in="formData",required=true,type="string",description="任务名称"),
     *  @SWG\Parameter(name="is_free",in="formData",type="integer",enum={0,1},description="是否免费"),
     *  @SWG\Parameter(name="is_optional",in="formData",type="integer",enum={0,1},description="是否可选"),
     *  @SWG\Parameter(name="length",in="formData",type="string",description="长度;单位：秒、页等"),
     *  @SWG\Parameter(name="started_at",in="formData",type="string",description="任务开始时间:2018-12-12"),
     *  @SWG\Parameter(name="ended_at",in="formData",type="string",description="任务结束时间:2018-12-13"),
     *  @SWG\Parameter(name="finish_type",in="formData",type="string",enum={"time","end"},description="完成类型"),
     *  @SWG\Parameter(name="finish_detail",in="formData",type="integer",description="指定完成条件"),
     *  @SWG\Parameter(name="media_uri",in="formData",type="string",description="资源名称（七牛的名称）；当类型为考试时，则为考试ID；当类型为图文时，则无需填写；"),
     *  @SWG\Parameter(name="body",in="formData",type="string",description="当 type 为 text 时，该值必须填写，否则忽略"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Task"),
     *  @SWG\Response(response=403,description="请求被拒绝"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Chapter $chapter, TaskRequest $request, Task $task)
    {
        $this->authorize('isPlanTeacher', $plan = $chapter->plan);

        $taskCount = $chapter->tasks()->count();

        $model = resolve('App\\Models\\' . studly_case($request->target_type));

        // 资源信息
        switch ($request->target_type) {
            // 视频、音频、PPT、DOC
            case 'video':
            case 'audio':
            case 'ppt':
            case 'doc':
                $media = $model->where($request->only('media_uri'))->first();
                break;
            // 图文
            case 'text':
                $media = $model->create($request->only(['body']));
                break;
            // 考试 此处传递的是ID
            case 'test':
                $media = $model->find($request->media_uri);
                break;
            default:
                $media = null;
        }

        if (!$media) {
            $this->response->error(__('Recourse loss.'), 400);
        }

        $task->fill(request()->only([
            'title',
            'type',
            'is_free',
            'is_optional',
            'length',
            'started_at',
            'ended_at',
            'finish_type',
            'finish_detail'
        ]));
        $task->user_id = auth()->id();
        $task->course_id = $plan->course_id;
        $task->plan_id = $plan->id;
        $task->chapter_id = $chapter->id;
        $task->target_type = $request->target_type;
        $task->target_id = $media->id;
        $task->seq = $taskCount + 1;
        $task->save();

        return $this->response->item($task, new TaskTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/chapters/{chapter_id}/tasks/{task_id}",
     *  tags={"web/task"},
     *  summary="任务更新",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(name="task_id",in="path",type="integer",required=true,description="章节ID",description="任务ID"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-target_type"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-type"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-title"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-is_free"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-is_optional"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-length"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-started_at"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-ended_at"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-finish_type"),
     *  @SWG\Parameter(ref="#/parameters/TaskForm-finish_detail"),
     *  @SWG\Parameter(name="media_uri",in="formData",type="string",description="媒体资源名称（七牛）；当为图文或考试时，即为资源ID"),
     *  @SWG\Parameter(name="body",in="formData",type="string",description="当类型为图文时，请填写此项，除此以外请忽略"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Task")),
     *  @SWG\Response(response=403,description="请求被拒绝"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Chapter $chapter, $task, TaskRequest $request)
    {
        $this->authorize('isPlanTeacher', $chapter->plan);

        $task = $chapter->tasks()->findOrFail($task);

        // 仅更改基本信息
        if ($task->target->media_uri && $request->media_uri === $task->target->media_uri) {
            $task->fill($request->only([
                'title', 'type', 'is_free', 'is_optional', 'length', 'started_at', 'ended_at', 'finish_type', 'finish_detail'
            ]));
            $task->save();

            return $this->response->noContent();
        }

        $model = resolve('App\\Models\\' . studly_case($request->target_type));

        // 更改资源信息
        switch ($request->target_type) {
            // 视频、音频、PPT、DOC
            case 'video':
            case 'audio':
            case 'ppt':
            case 'doc':
                $media = $model->where($request->only('media_uri'))->first();
                break;
            // 图文、考试 此处传递的是ID
            case 'text':
                $media = $model->find($request->media_uri);
                break;
            case 'test':
                $media = $model->find($request->media_uri);
                break;
            default:
                $media = null;
        }

        if (!$media) {
            $this->response->error(__('Recourse loss.'), 400);
        }

        DB::transaction(function () use ($media, $chapter, $task) {
            if ($media instanceof Text) {
                $media->body = request('body');
                $media->save();
            }

            $task->fill(request()->only([
                'title',
                'type',
                'is_free',
                'is_optional',
                'started_at',
                'ended_at',
                'finish_type',
                'finish_detail'
            ]));
            $task->target_type = request('target_type');
            $task->target_id = $media->id;
            $task->length = $media->length ?? 0;
            $task->save();
            $task->save();
        });

        return $this->response->item($task, new TaskTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Delete(
     *  path="/manage/chapters/{chapter_id}/tasks/{task_id}",
     *  tags={"web/task"},
     *  summary="任务删除",
     *  description="当删除任务时，同时也要删除学员对此任务的学习状况 task_results",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(name="task_id",in="path",type="integer",required=true,description="任务ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=403,description="请求被拒绝"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Chapter $chapter, $task)
    {
        $this->authorize('isPlanTeacher', $chapter->plan);

        $task = $chapter->tasks()->findOrFail($task);

        /**
         * TODO 可以移入到 Model Observer 或 Listen/Event
         * 当删除任务时：
         * 1. 如果任务已发布，那么对 plan 的 tasks_count 进行递减操作；否则不操作。(权限中做了已发布任务无法删除的限制，所以此项不用考虑)
         * 2. 如果任务结果已完成，那么对 PlanMember 中的 learned_count 执行递减操作；否则不操作。
         * 3. 删除任务结果
         *
         * 注：当恢复时，反顺序恢复即可。
         */

        $plan = $task->plan;
        $results = $task->results;

        DB::transaction(function () use ($task, $plan, $results) {
            // 对 plan_member 中的 learned_count 做处理
            $results->each(function ($result) use ($task, $plan) {
                if ($result->status == TaskResultStatus::FINISH) {
                    !$task->is_optional && PlanMember::where('user_id', $result->user_id)->where('plan_id', $plan->id)->decrement('learned_compulsory_count');
                    PlanMember::where('user_id', $result->user_id)->where('plan_id', $plan->id)->decrement('learned_count');
                }
            });
            // 删除任务结果
            TaskResult::whereIn('id', $results->pluck('id')->toArray())->delete();
            // 删除任务
            $task->delete();
        });

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/manage/chapters/{chapter_id}/tasks/{task_id}/publish",
     *  tags={"web/task"},
     *  summary="任务发布/取消/关闭",
     *  description="",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",description=""),
     *  @SWG\Parameter(name="task_id",in="path",type="integer",description=""),
     *  @SWG\Parameter(name="status",in="formData",type="string",enum={"published","closed"},description="草稿、发布、关闭",default="draft"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=403,description="请求被拒绝"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function publish(Chapter $chapter, $task)
    {
        $this->authorize('isPlanTeacher', $chapter->plan);

        $this->validate(request(), ['status' => 'required|in:published,closed']);

        $task = $chapter->tasks()->findOrFail($task);

        switch ($task->target_type) {
            case TaskTargetType::VIDEO:
                // 判断是否切片成功
                $video = $task->target;
                if ($video->status !== VideoStatus::SLICED) {
                    $this->response->errorBadRequest(__('Video cannot be published without slicing.'));
                }
                break;
            case TaskTargetType::TEST:
                // 判断是否包含题目
                $test = $task->target;
                if ($test->questions_count <= 0) {
                    $this->response->errorBadRequest(__('Exam cannot be published without questions.'));
                }
                break;
            case TaskTargetType::AUDIO:
            case TaskTargetType::PPT:
            case TaskTargetType::DOC:
            case TaskTargetType::TEXT:
                break;
            default:
                $this->response->errorBadRequest(__('Task cannot be published with type errors.'));
        }

        $task->status = request('status');
        $task->save();

        return $this->response->noContent();
    }
}