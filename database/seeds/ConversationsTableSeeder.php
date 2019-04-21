<?php

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ConversationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::unsetEventDispatcher();

        $conversations = [
            ['from' => 1, 'to' => 2, 'uuid' => Uuid::uuid4()->toString(), 'message' => 'Hello 1'],
            ['from' => 2, 'to' => 1, 'uuid' => Uuid::uuid4()->toString(), 'message' => 'Hello 2'],
        ];

        foreach ($conversations as $group) {
            // 判断是否存在会话，如果不存在，则创建一个
            $userConversation = Conversation::getConversationBetween($group['from'], $group['to']);
            $anotherConversation = Conversation::getConversationBetween($group['to'], $group['from']);

            if ($userConversation) {
                $conversationUuid = $userConversation->uuid;
            } else if ($anotherConversation) {
                $conversationUuid = $anotherConversation->uuid;
            } else {
                $conversationUuid = Uuid::uuid4()->toString();
            }

            if (!$userConversation) {
                $userConversation = new Conversation(['user_id' => $group['from'], 'another_id' => $group['to']]);
                $userConversation->user_id = $group['from'];
                $userConversation->uuid = $conversationUuid;
                $userConversation->save();
            }

            if (!$anotherConversation) {
                $anotherConversation = new Conversation(['user_id' =>  $group['to'], 'another_id' => $group['from']]);
                $anotherConversation->user_id = $group['to'];
                $anotherConversation->uuid = $conversationUuid;
                $anotherConversation->save();
            }

            $userMessage = new Message([
                'sender_id' =>  $group['from'],
                'recipient_id' => $group['to'],
                'conversation_id' => $userConversation->id,
                'body' => $group['message'],
                'uuid' => $group['uuid']
            ]);
            $userMessage->save();

            $anotherMessage = new Message([
                'sender_id' =>  $group['from'],
                'recipient_id' => $group['to'],
                'conversation_id' => $anotherConversation->id,
                'body' => $group['message'],
                'uuid' => $group['uuid']
            ]);
            $anotherMessage->save();
        }
    }
}
