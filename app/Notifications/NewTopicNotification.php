<?php

namespace App\Notifications;

use App\Models\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * 话题提醒
 * 支持情景：新增话题时，为关注人发送的提醒
 */
class NewTopicNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $topic;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Topic $topic)
    {
        //
        $this->topic = $topic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * 版本下发表话题，粉丝发送通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => '话题/问答提醒',
            'content' => "您关注的 <a href='#'>{$this->topic->user->username}</a> 发布了新的话题/问答",
            'topic_id' => $this->topic->id
        ];
    }
}
