<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\MessageTransformer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @SWG\GET(
     *  path="/admin/manage-messages",
     *  tags={"admin/conversation"},
     *  summary="私信列表（全部）",
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
    public function manage()
    {
        $data = Message::filtered()->orderBy('created_at', 'desc')->groupBy('uuid')->paginate(self::perPage());

        return $this->response->paginator($data, new MessageTransformer());
    }

    /**
     * @SWG\GET(
     *  path="/admin/conversations/{conversation_id}/messages",
     *  tags={"admin/conversation"},
     *  summary="私信列表",
     *  description="",
     *  @SWG\Parameter(name="conversation_id",in="path",type="integer",description="会话ID"),
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
    public function index(Conversation $conversation)
    {
        $data = $conversation->messages()->orderBy('created_at', 'desc')->paginate(self::perPage());

        return $this->response->paginator($data, new MessageTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/admin/messages",
     *  tags={"admin/conversation"},
     *  summary="单个删除/批量删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="message_uuids",in="formData",type="array",@SWG\Items(type="integer"),description="私信UUID数组"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'message_uuids' => ['required', 'array', 'distinct']
        ]);

        Message::whereIn('uuid', $request->message_uuids)->delete();

        return $this->response->noContent();
    }
}
