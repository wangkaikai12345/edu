<?php

namespace App\Notifications;

use App\Models\Follow;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * 关注通知
 * 支持情景：新增关注时，给被关注人发送的提醒
 */
class FollowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $follow;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Follow $follow)
    {
        //
        $this->follow = $follow;
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
     * 关注发送关注通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => '关注提醒',
            'content' => "<a href='".route('users.show', $this->follow->user)."'>{$this->follow->user->username}</a> 关注了你。",
        ];
    }
}
