<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TopicRequest;
use App\Http\Transformers\TopicTransformer;
use App\Models\Plan;
use App\Models\Topic;

class TopicController extends Controller
{
    /**
     * @SWG\Tag(name="web/topic",description="前台话题")
     */

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/topics",
     *  tags={"web/topic"},
     *  summary="列表",
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-type"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-is_stick"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-is_elite"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-is_audited"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-task_id"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-task:title"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-replies_count"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-hit_count"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-latest_replied_at"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-latest_replier_id"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-latest_replier:username"),
     *  @SWG\Parameter(ref="#/parameters/TopicQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/Topic-sort"),
     *  @SWG\Parameter(ref="#/parameters/Topic-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/TopicPagination")
     * )
     */
    public function index(Plan $plan)
    {
        self::orderBy();

        $topics = $plan->topics()->sorted()->paginate(self::perPage());

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/topics/{topic_id}",
     *  tags={"web/topic"},
     *  summary="详情",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="topic_id",in="path",type="integer",required=true,description="话题ID"),
     *  @SWG\Parameter(ref="#/parameters/Topic-include"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Topic"))
     * )
     */
    public function show(Plan $plan, $topic)
    {
        $item = $plan->topics()->findOrFail($topic);

        return $this->response->item($item, new TopicTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/plans/{plan_id}/topics",
     *  tags={"web/topic"},
     *  summary="添加",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="type",in="formData",type="string",enum={"discussion","question"},required=true,description="类型"),
     *  @SWG\Parameter(name="task_id",in="formData",type="integer",default=null,description="任务ID"),
     *  @SWG\Parameter(name="title",in="formData",type="string",required=true,maxLength=191,description="题目"),
     *  @SWG\Parameter(name="content",in="formData",type="string",required=true,description="内容"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Topic"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Plan $plan, TopicRequest $request)
    {
        $this->authorize('isMember', $plan);

        $topic = new Topic($request->all());
        $topic->user_id = auth()->id();
        $topic->course_id = $plan->course_id;
        $topic->plan_id = $plan->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/plans/{plan_id}/topics/{topic_id}",
     *  tags={"web/topic"},
     *  summary="更新",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer"),
     *  @SWG\Parameter(name="topic_id",in="path",type="integer"),
     *  @SWG\Parameter(name="title",in="formData",type="string",description="题目"),
     *  @SWG\Parameter(name="content",in="formData",type="string",description="内容"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Plan $plan, $topic)
    {
        $topic = Topic::where('plan_id', $plan->id)->findOrFail($topic);

        $this->authorize('isAuthor', $topic);

        $topic->fill(request()->all());
        $topic->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/plans/{plan_id}/topics/{topic_id}",
     *  tags={"web/topic"},
     *  summary="删除",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="topic_id",in="path",type="integer",required=true,description="话题ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Plan $plan, $topic)
    {
        $topic = Topic::where('plan_id', $plan->id)->findOrFail($topic);

        $this->authorize('isAuthor', $topic);

        $topic->delete();

        return $this->response->noContent();
    }
}
