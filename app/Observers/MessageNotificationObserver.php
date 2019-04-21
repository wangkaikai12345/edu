<?php

namespace App\Observers;

use App\Models\MessageNotification;

class MessageNotificationObserver
{
    /**
     * 监听发送私信事件
     *
     * @param MessageNotification $notification
     */
    public function saved(MessageNotification $notification)
    {
        $notification->user->increment('new_messages_count');
    }
}