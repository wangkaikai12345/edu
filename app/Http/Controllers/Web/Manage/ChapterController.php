<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ChapterRequest;
use App\Http\Transformers\ChapterTransformer;
use App\Models\Chapter;
use App\Models\Plan;
use App\Models\Task;
use DB;

class ChapterController extends Controller
{
    /**
     * @SWG\Tag(name="web/chapter",description="章节")
     */

    /**
     * @SWG\Get(
     *  path="/manage/plans/{plan_id}/chapters",
     *  tags={"web/chapter"},
     *  summary="章节列表",
     *  description="按 seq 的升序排序",
     *  @SWG\Parameter(name="plan_id",in="path",required=true,type="integer",description="版本ID"),
     *  @SWG\Parameter(ref="#/parameters/Chapter-include"),
     *  @SWG\Response(response=200,ref="#/responses/ChapterResponse"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->chapters()->orderBy('seq')->where(['parent_id' => 0])->get();

        return $this->response->collection($data, new ChapterTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/plans/{plan_id}/chapters",
     *  tags={"web/chapter"},
     *  summary="章节添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(ref="#/parameters/ChapterForm-parent_id"),
     *  @SWG\Parameter(ref="#/parameters/ChapterForm-title"),
     *  @SWG\Response(response=201,description="",ref="#/definitions/Chapter"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Plan $plan, ChapterRequest $request)
    {
        $this->authorize('isPlanTeacher', $plan);

        // 查询最大排序顺序
        $maxSeq = $plan->chapters()->where('parent_id', request('parent_id', 0))->max('seq') ?? 0;

        $chapter = new Chapter($request->all());
        $chapter->course_id = $plan->course_id;
        $chapter->plan_id = $plan->id;
        $chapter->user_id = auth()->id();
        $chapter->seq = $maxSeq + 1;
        $chapter->save();

        return $this->response->item($chapter, new ChapterTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/plans/{plan_id}/chapters/{chapter_id}",
     *  tags={"web/chapter"},
     *  summary="章节更新",
     *  description="允许更改父级ID，即更换章节",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Parameter(ref="#/parameters/ChapterForm-title"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Plan $plan, $chapterId, ChapterRequest $request)
    {
        $this->authorize('isPlanTeacher', $plan);

        $chapter = $plan->chapters()->findOrFail($chapterId);

        $chapter->title = $request->title;
        $chapter->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/plans/{plan_id}/chapters/{chapter_id}",
     *  tags={"web/chapter"},
     *  summary="章节删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="chapter_id",in="path",type="integer",required=true,description="章节ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Plan $plan, $chapterId)
    {
        $this->authorize('isPlanTeacher', $plan);

        $chapter = $plan->chapters()->findOrFail($chapterId);

        // 如果是章，则检查是存在节
        if ($chapter->parent_id == 0 && $chapter->children()->count()) {
            $this->response->errorForbidden(__('Child node exists.'));
        } // 如果是节，则检查节下是否存在任务
        else if ($chapter->parent_id != 0 && $chapter->tasks()->count()) {
            $this->response->errorForbidden(__('Child node exists.'));
        }
        $chapter->delete();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/manage/plans/{plan_id}/chapters/sort",
     *  tags={"web/chapter"},
     *  summary="章节排序",
     *  description="注意：只能同级别进行排序。比如全部的章或者章下的节、再或者节下的任务",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="plan_id",in="path",required=true,type="integer",description="版本ID"),
     *  @SWG\Parameter(name="type",in="formData",type="string",enum={"chapter","section","task"},description="章排序、节排序、任务排序"),
     *  @SWG\Parameter(name="parent_id",in="formData",type="integer",description="父级ID；若为章时，则允许为空；若为节时，则为节从属章的ID；若为任务时，则为任务所属节；"),
     *  @SWG\Parameter(name="target_id",in="formData",type="integer",description="被移动的ID"),
     *  @SWG\Parameter(name="before_id",in="formData",type="integer",description="在哪一个ID之前，当为空时，则默认设置为最后一项"),
     *  @SWG\Response(response=204,description=""),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function sort(Plan $plan, ChapterRequest $request)
    {
        $this->authorize('isPlanTeacher', $plan);

        $parentId = (int)$request->parent_id;
        $targetId = (int)$request->target_id;
        $beforeId = $request->input('before_id', false);

        switch ($request->type) {
            case 'chapter':
            case 'section':
                $chapters = Chapter::where('plan_id', $plan->id)
                    ->where('parent_id', $parentId)
                    ->orderBy('seq')->pluck('id')->toArray();

                $targetIndex = array_search($targetId, $chapters);

                // 移除目标
                array_splice($chapters, $targetIndex, 1);
                if ($beforeId){
                    // 移动到目标位置
                    $beforeIndex = array_search($beforeId, $chapters);
                    array_splice($chapters, $beforeIndex, 0, $targetId);
                } else {
                    // 移动到最后位置
                    array_push($chapters, $targetId);
                }
                DB::transaction(function () use ($chapters) {
                    foreach ($chapters as $index => $id) {
                        Chapter::where('id', $id)->update(['seq' => $index + 1]);
                    }
                });
                break;
            case 'task':
                $tasks = Chapter::find($parentId)->tasks()->orderBy('seq')->pluck('id')->toArray();

                $targetIndex = array_search($targetId, $tasks);

                // 移除目标
                array_splice($tasks, $targetIndex, 1);

                if ($beforeId){
                    // 移动到指定位置
                    $beforeIndex = array_search($beforeId, $tasks);
                    array_splice($tasks, $beforeIndex, 0, $targetId);
                } else {
                    // 移动到最后位置
                    array_push($tasks, $targetId);
                }
                DB::transaction(function () use ($tasks) {
                    foreach ($tasks as $index => $id) {
                        Task::where('id', $id)->update(['seq' => $index + 1]);
                    }
                });
                break;
        }

        return $this->response->noContent();
    }
}
