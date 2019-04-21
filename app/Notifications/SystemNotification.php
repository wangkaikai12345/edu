<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 系统通知
 * 支持情景：管理员为所有用户发送的通知
 */
class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $notifyData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notifyData)
    {
        $this->notifyData = $notifyData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $title = data_get($this->notifyData, 'title');
        $content = data_get($this->notifyData, 'content');

        return [
            'title' => $title,
            'content' => $content
        ];
    }
}
