<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Transformers\MessageTransformer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    /**
     * 私信列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manage(Request $request)
    {
        $messages = Message::filtered(array_filter($request->all()))
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->groupBy('uuid')
            ->paginate(self::perPage());

        return view('admin.message.index', compact('messages'));
    }


    /**
     * @param Conversation $conversation
     * @return \Dingo\Api\Http\Response
     */
    public function index(Conversation $conversation)
    {
        $data = $conversation->messages()->orderBy('created_at', 'desc')->paginate(self::perPage());

        return $this->response->paginator($data, new MessageTransformer());
    }


    /**
     * 删除
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
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
