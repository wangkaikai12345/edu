<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TopicStatus;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TopicTransformer;
use App\Models\Topic;
use App\Rules\CustomEnumRule;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/admin/topics",
     *   tags={"admin/topic"},
     *   summary="列表",
     *   description="",
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-title"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-type"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-is_stick"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-is_elite"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-is_audited"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-user_id"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-user:username"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-course_id"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-course:title"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-plan_id"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-plan:title"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-task_id"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-task:title"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-replies_count"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-hit_count"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-latest_replied_at"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-latest_replier_id"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-latest_replier:username"),
     *   @SWG\Parameter(ref="#/parameters/TopicQuery-status"),
     *   @SWG\Parameter(ref="#/parameters/Topic-sort"),
     *   @SWG\Parameter(ref="#/parameters/Topic-include"),
     *   @SWG\Parameter(ref="#/parameters/page"),
     *   @SWG\Parameter(ref="#/parameters/per_page"),
     *   @SWG\Response(response=200,ref="#/responses/TopicPagination"),
     *   security={
     *      {"Bearer": {}}
     *   },
     * )
     */
    public function index(Topic $topic)
    {
        $data = $topic->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new TopicTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/topics/{topic_id}",
     *  tags={"admin/topic"},
     *  summary="状态更新",
     *  description="",
     *  @SWG\Parameter(in="path",name="topic_id",type="integer",required=true,description="话题ID"),
     *  @SWG\Parameter(ref="#/parameters/TopicForm-status"),
     *  @SWG\Parameter(ref="#/parameters/TopicForm-is_stick"),
     *  @SWG\Parameter(ref="#/parameters/TopicForm-is_elite"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Request $request, Topic $topic)
    {
        $this->validate($request, [
            'status' => ['sometimes', new CustomEnumRule(TopicStatus::class)],
            'is_stick' => ['sometimes', 'boolean'],
            'is_elite' => ['sometimes', 'boolean'],
        ]);

        $request->has('status') && $topic->status = $request->status;
        $request->has('is_stick') && $topic->is_stick = (bool)$request->is_stick;
        $request->has('is_elite') && $topic->is_elite = (bool)$request->is_elite;
        $topic->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/topics/{topic_id}",
     *  tags={"admin/topic"},
     *  summary="删除",
     *  description="",
     *  @SWG\Parameter(in="path",name="topic_id",type="integer",required=true,description="话题ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return $this->response->noContent();
    }
}
