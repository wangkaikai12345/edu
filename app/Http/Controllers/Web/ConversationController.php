<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ConversationTransformer;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageNotification;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

/**
 * @SWG\Path(
 *   path="/conversations/{conversation_id}",
 *   @SWG\Parameter(name="conversation_id",type="integer",in="path",required=true,description="会话ID"),
 * )
 */
class ConversationController extends Controller
{
    /**
     * @SWG\Tag(name="web/conversation",description="会话")
     */

    /**
     * @SWG\Get(
     *  path="/conversations",
     *  tags={"web/conversation"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-another_id"),
     *  @SWG\Parameter(ref="#/parameters/ConversationQuery-another:username"),
     *  @SWG\Parameter(ref="#/parameters/Conversation-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/ConversationPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        // 获取当前用户的会话列表，并读取当前会话的未读消息数。
        $conversations = Conversation::where('user_id', auth()->id())
            ->withCount(['notifications', 'messages' => function ($query) {
                return $query->where('user_id', auth()->id());
            }])->latest()
            ->paginate(self::perPage());

        return $this->response->paginator($conversations, new ConversationTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/conversations",
     *  tags={"web/conversation"},
     *  summary="消息发送",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="user_id",in="formData",type="string",required=true,description="收信人"),
     *  @SWG\Parameter(name="message",in="formData",type="string",required=true,maxLength=1000,description="消息内容"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Conversation"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'message' => 'required|max:1000'
        ]);

        $user = auth()->user();
        $another = User::findOrFail($request->user_id);

        /**
         * 1.一个会话的创建，称其为原始会话，其附带会产生接收人的会话，称其为附属会话，二者互不影响
         * 2.原始会话与附属会话，通过 UUID 关联，标记其为相同会话
         * 3.一条消息的发送，向其自身的会话之中添加一条消息，其为原始消息，再向附属会话添加一条消息，其为附属消息
         * 4.原始消息与附属消息，通过 UUID 关联，标记其为相同消息
         */
        $userConversation = Conversation::getConversationBetween($user->id, $another->id);
        $anotherConversation = Conversation::getConversationBetween($another->id, $user->id);

        if ($userConversation) {
            $conversationUuid = $userConversation->uuid;
        } else if ($anotherConversation) {
            $conversationUuid = $anotherConversation->uuid;
        } else {
            $conversationUuid = Uuid::uuid4()->toString();
        }
        $userConversation = \DB::transaction(function () use ($user, $another, $userConversation, $anotherConversation, $conversationUuid) {
            // 所有者会话
            if (!$userConversation) {
                $userConversation = new Conversation();
                $userConversation->user_id = $user->id;
                $userConversation->another_id = $another->id;
                $userConversation->uuid = $conversationUuid;
                $userConversation->save();
            }
            // 参与者会话
            if (!$anotherConversation) {
                $anotherConversation = new Conversation();
                $anotherConversation->user_id = $another->id;
                $anotherConversation->another_id = $user->id;
                $anotherConversation->uuid = $conversationUuid;
                $anotherConversation->save();
            }
            // 所属人会话消息
            $message = new Message();
            $message->sender_id = $user->id;
            $message->recipient_id = $another->id;
            $message->conversation_id = $userConversation->id;
            $message->body = request('message');
            $message->uuid = Uuid::uuid4()->toString();
            $message->save();
            // 参与者会话消息
            $anotherMessage = new Message();
            $anotherMessage->sender_id = $message->sender_id;
            $anotherMessage->recipient_id = $message->recipient_id;
            $anotherMessage->conversation_id = $anotherConversation->id;
            $anotherMessage->body = $message->body;
            $anotherMessage->uuid = $message->uuid;
            $anotherMessage->save();
            // 最后一条消息更新
            $userConversation->last_message_id = $message->id;
            $userConversation->save();
            $anotherConversation->last_message_id = $anotherMessage->id;
            $anotherConversation->save();
            // 消息提醒
            $notification = new MessageNotification();
            $notification->message_id = $another;
            $notification->conversation_id = $anotherConversation->id;
            $notification->user_id = $another->id;
            $notification->save();

            return $userConversation;
        });

        $userConversation->notifications_count = $userConversation->notifications()->count();
        $userConversation->messages_count = $userConversation->messages()->count();

        return $this->response->item($userConversation, new ConversationTransformer())->setStatusCode(201);
    }

    /**
     * 检查系统发送私信配置
     *
     * @param $send
     * @param $receive
     */
    protected function checkMessageConfig($send, $receive)
    {
        // 获取发送私信配置
        $messageSet = Setting::where('namespace', SettingType::MESSAGE)->value('value');

        // 获取用户角色
        $sendRole = $send->roles()->value('name');
        $receiveRole = $receive->roles()->value('name');

        // 判断
        if ($messageSet) {
            if (empty($messageSet['allow_user_to_user']) && $sendRole == 'student' && $receiveRole == 'student') {
                $this->response->errorBadRequest(__('Student-to-student\'s Message is forbidden.'));
            }

            if (empty($messageSet['allow_user_to_teacher']) && $sendRole == 'student' && $receiveRole == 'teacher') {
                $this->response->errorBadRequest(__('Student-to-teacher\'s Message is forbidden.'));
            }

            if (empty($messageSet['allow_teacher_to_user']) && $sendRole == 'teacher' && $receiveRole == 'student') {
                $this->response->errorBadRequest(__('Teacher-to-student\'s Message is forbidden.'));
            }
        }
    }

    /**
     * @SWG\Get(
     *  path="/conversations/{conversation_id}",
     *  tags={"web/conversation"},
     *  summary="会话详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Conversation"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show($conversation)
    {
        $conversation = Conversation::where('user_id', auth()->id())
            ->withCount(['notifications', 'messages' => function ($query) {
                return $query->where('user_id', auth()->id());
            }])
            ->findOrFail($conversation);

        return $this->response->item($conversation, new ConversationTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/conversations/{conversation_id}",
     *  tags={"web/conversation"},
     *  summary="会话删除",
     *  description="清空会话关联消息，并清除所有提醒以及会话本身",
     *  produces={"application/json"},
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy($conversation)
    {
        $me = auth()->user();

        $conversation = Conversation::where('user_id', $me->id)->findOrFail($conversation);

        $newMessageCount = $conversation->notifications()->where('is_seen', 0)->count();

        $num = $me->new_messages_count - $newMessageCount;
        \DB::transaction(function () use ($conversation, $num, $me) {
            $me->new_messages_count = $num > 0 ? $num : 0;
            $me->save();
            // 清除相关提醒、私信、本身
            $conversation->notifications()->delete();
            $conversation->messages()->delete();
            $conversation->delete();
        });

        return $this->response->noContent();
    }
}
