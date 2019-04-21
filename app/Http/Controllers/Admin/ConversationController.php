<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ConversationTransformer;
use App\Models\Conversation;

class ConversationController extends Controller
{
    /**
     * @SWG\Tag(name="admin/conversation",description="会话")
     */

    /**
     * @SWG\GET(
     *  path="/admin/conversations",
     *  tags={"admin/conversation"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-another_id"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-another:username"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-uuid"),
     *  @SWG\Parameter(ref="#/parameters/Conversation-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/ConversationPagination"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index(Conversation $conversation)
    {
        $data = $conversation->filtered()->sorted()->groupBy('uuid')->paginate(self::perPage());

        return $this->response->paginator($data, new ConversationTransformer());
    }

    /**
     * @SWG\GET(
     *  path="/admin/conversations/{conversation_id}",
     *  tags={"admin/conversation"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="conversation_id",type="integer",in="path",required=true,description="会话ID"),
     *  @SWG\Parameter(ref="#/parameters/Conversation-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Conversation"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function show(Conversation $conversation)
    {
        return $this->response->item($conversation, new ConversationTransformer());
    }
}
