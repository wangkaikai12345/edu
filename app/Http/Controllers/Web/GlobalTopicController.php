<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TopicTransformer;
use App\Models\Topic;

class GlobalTopicController extends Controller
{
    /**
     * @SWG\Tag(name="web/topic",description="话题")
     */

    /**
     * @SWG\Get(
     *   path="/topics",
     *   tags={"web/topic"},
     *   summary="列表（全局）",
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
     *   @SWG\Response(response=200,ref="#/responses/TopicPagination")
     * )
     */
    public function index(Topic $topic)
    {
        $topics = $topic->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * @SWG\Get(
     *   path="/topics/{topic_id}",
     *   tags={"web/topic"},
     *   summary="详情（全局）",
     *   @SWG\Parameter(name="topic_id",in="path",required=true,type="integer"),
     *   @SWG\Parameter(ref="#/parameters/Topic-include"),
     *   @SWG\Response(response=200,description="ok",@SWG\Schema(ref="#/definitions/Topic"))
     * )
     */
    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }
}
