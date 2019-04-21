<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageNotification;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ConversationController extends Controller
{
    /**
     * 我的对话首页,会话列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function index()
    {
        // 获取当前用户的会话列表
        $conversations = Conversation::where('user_id', auth('web')->id())
            ->latest()->paginate(config('theme.my_notify_num'));

        return frontend_view('message.index', compact('conversations'));
    }

    /**
     * 发送私信
     *
     * @param Request $request
     * @return $this
     * @author 王凯
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'message' => 'required|max:1000'
        ]);

        $user = auth('web')->user();
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

        if ($request->ajax()) {
            return ajax('200', $userConversation ? '发送消息成功' : '创建会话成功', ['route' => route('users.message.show', $userConversation['id'])]);
        } else {
            return back()->with('success', '发送成功');
        }
    }

    /**
     * 检查系统发送私信配置
     *
     * @param $send
     * @param $receive
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
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
                return ajax('400', __('Student-to-student\'s Message is forbidden.'));
            }

            if (empty($messageSet['allow_user_to_teacher']) && $sendRole == 'student' && $receiveRole == 'teacher') {
                return ajax('400', __('Student-to-teacher\'s Message is forbidden.'));
            }

            if (empty($messageSet['allow_teacher_to_user']) && $sendRole == 'teacher' && $receiveRole == 'student') {
                return ajax('400', __('Teacher-to-student\'s Message is forbidden.'));
            }
        }
    }

    /**
     * 会话详情信息，消息列表
     *
     * @param $conversation
     * @return \Dingo\Api\Http\Response
     * @author
     */
    public function show($conversation)
    {
        $conversation = Conversation::where('user_id', auth('web')->id())->findOrFail($conversation);

        return frontend_view('message.detail', compact('conversation'));
    }

    /**
     * 删除会话
     *
     * @param $conversation
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function destroy(Conversation $conversation)
    {
        $me = auth('web')->user();

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

        return ajax('200', '会话删除成功');
    }

    /**
     * 删除私信消息
     *
     * @param Conversation $conversation
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function destroyMessage(Conversation $conversation, $message)
    {
        if ($conversation->user_id != auth('web')->id()) {
            return ajax('400', __('Operation is not supported.'));
        }

        $message = $conversation->messages()->find($message);

        $message->delete();

        return ajax('200', '消息删除成功');
    }
}
