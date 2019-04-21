<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\MessageTransformer;
use App\Models\Conversation;

class MessageController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/conversations/{conversation_id}/messages",
     *  tags={"web/conversation"},
     *  summary="私信列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/MessageQuery-sender_id"),
     *  @SWG\Parameter(ref="#/parameters/MessageQuery-sender:username"),
     *  @SWG\Parameter(ref="#/parameters/MessageQuery-recipient_id"),
     *  @SWG\Parameter(ref="#/parameters/MessageQuery-recipient:username"),
     *  @SWG\Parameter(ref="#/parameters/Message-sort"),
     *  @SWG\Parameter(ref="#/parameters/Message-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/MessagePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index($conversation)
    {
        $me = auth()->user();

        /**
         * 当会话记录存在未读消息时被查看时，移除未读私信数，更新 user 的 new_message_count；
         * 当回话记录不存在未读消息时被查看时，不做任何操作。
         */
        $conversation = Conversation::where('user_id', $me->id)->findOrFail($conversation);
        if ($count = $conversation->notifications()->where('user_id', $me->id)->count()) {
            $num = $me->new_messages_count - $count;
            \DB::transaction(function () use ($conversation, $me, $num) {
                // 设置已读
                $conversation->notifications()->where('user_id', $me->id)->update(['is_seen' => 1]);
                // 减去用户新私信数
                $me->new_messages_count = $num > 0 ? $num : 0;
                $me->save();
            });
        }

        $messages = $conversation->messages()->latest()->paginate(self::perPage());

        return $this->response->paginator($messages, new MessageTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/conversations/{conversation_id}/messages/{message_id}",
     *  tags={"web/conversation"},
     *  summary="私信删除",
     *  description="",
     *  @SWG\Parameter(name="conversation_id",in="path",required=true,type="string",description="会话ID"),
     *  @SWG\Parameter(name="message_id",in="path",required=true,type="string",description="消息ID"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Conversation $conversation, $message)
    {
        if ($conversation->user_id != auth()->id()) {
            $this->response->errorForbidden(__('Operation is not supported.'));
        }

        $message = $conversation->messages()->find($message);

        $message->delete();

        return $this->response->noContent();
    }
}
