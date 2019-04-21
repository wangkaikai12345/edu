<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ConversationTransformer;
use App\Models\Conversation;

class ConversationController extends Controller
{

    public function index(Conversation $conversation)
    {
        $data = $conversation->filtered()->sorted()->groupBy('uuid')->paginate(self::perPage());

        return $this->response->paginator($data, new ConversationTransformer());
    }

    public function show(Conversation $conversation)
    {
        return $this->response->item($conversation, new ConversationTransformer());
    }
}
