<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ReplyRequest;
use App\Http\Transformers\ReplyTransformer;
use App\Models\Reply;
use App\Models\Topic;

class ReplyController extends Controller
{
    /**
     * @SWG\Tag(name="web/reply",description="话题回复")
     */

    /**
     * @SWG\Get(
     *  path="/topics/{topic_id}/replies",
     *  tags={"web/reply"},
     *  summary="列表",
     *  @SWG\Parameter(name="topic_id",in="path",type="integer",required=true,description="话题ID"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-is_elite"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-plan:title"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-task_id"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-task:title"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-topic_id"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-topic:title"),
     *  @SWG\Parameter(ref="#/parameters/ReplyQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/Reply-sort"),
     *  @SWG\Parameter(ref="#/parameters/Reply-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/ReplyPagination")
     * )
     */
    public function index(Topic $topic)
    {
        $data = $topic->replies()->orderByDesc('created_at')->paginate(self::perPage());

        return $this->response->paginator($data, new ReplyTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/topics/{topic_id}/replies/{reply_id}",
     *  tags={"web/reply"},
     *  summary="详情",
     *  @SWG\Parameter(name="topic_id",in="path",type="integer"),
     *  @SWG\Parameter(name="reply_id",in="path",type="integer"),
     *  @SWG\Parameter(ref="#/parameters/Reply-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Reply")
     * )
     */
    public function show(Topic $topic, $reply)
    {
        $item = $topic->replies()->findOrFail($reply);

        return $this->response->item($item, new ReplyTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/topics/{topic_id}/replies",
     *  tags={"web/reply"},
     *  summary="添加",
     *  @SWG\Parameter(name="topic_id",in="path",type="integer",required=true,description="话题ID"),
     *  @SWG\Parameter(name="content",in="formData",type="string",description="回复内容",required=true),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Reply"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(ReplyRequest $request, Topic $topic)
    {
        $reply = new Reply($request->all());
        $reply->course_id = $topic->course_id;
        $reply->plan_id = $topic->plan_id;
        $reply->topic_id = $topic->id;
        $reply->user_id = auth()->id();
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Delete(
     *  path="/topics/{topic_id}/replies/{reply_id}",
     *  tags={"web/reply"},
     *  summary="删除",
     *  @SWG\Parameter(name="topic_id",in="path",type="integer",required=true,description="话题ID"),
     *  @SWG\Parameter(name="reply_id",in="path",type="integer",required=true,description="回复ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Topic $topic, $reply)
    {
        $reply = $topic->replies()->findOrFail($reply);

        $this->authorize('isAuthorOrTopicAuthor', $reply);

        $reply->delete();

        return $this->response->noContent();
    }
}
