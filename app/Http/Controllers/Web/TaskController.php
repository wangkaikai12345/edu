<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TaskTransformer;
use App\Models\Chapter;

class TaskController extends Controller
{
    /**
     * @SWG\Tag(name="web/task",description="前台任务")
     */

    /**
     * @SWG\Get(
     *  path="/chapters/{chapter_id}/tasks",
     *  tags={"web/task"},
     *  summary="任务列表（普通用户）",
     *  description="仅支持展示已发布任务",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(ref="#/parameters/Task-sort"),
     *  @SWG\Parameter(ref="#/parameters/Task-include"),
     *  @SWG\Response(response=200,ref="#/responses/TaskResponse")
     * )
     */
    public function index(Chapter $chapter)
    {
        request()->offsetSet('status', 'published');

        $data = $chapter->tasks()->orderBy('seq')->filtered()->get();

        return $this->response->collection($data, new TaskTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/chapters/{chapter_id}/tasks/{task_id}",
     *  tags={"web/task"},
     *  summary="任务详情（普通用户）",
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(name="task_id",in="path",type="integer",required=true,description="任务ID"),
     *  @SWG\Parameter(ref="#/parameters/Task-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Task"),
     * )
     */
    public function show(Chapter $chapter, $task)
    {
        $task = $chapter->tasks()->findOrFail($task);

        return $this->response->item($task, new TaskTransformer());
    }
}