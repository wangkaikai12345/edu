<?php

namespace App\Notifications;

use App\Models\Chapter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 话题提醒
 * 支持情景：新增话题时，为关注人发送的提醒
 */
class HomeworkResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $chapter;
    public $classroom_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Chapter $chapter, $classroom_id)
    {
        //
        $this->chapter = $chapter;
        $this->classroom_id = $classroom_id;
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
     * 作业批阅完成，通知用户
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
         if ($this->classroom_id)
             return [
                 'title' => '作业批阅完成',
                 'content' => '您的作业已经批阅完成！ <a href='.route('tasks.show', [$this->chapter, 'type' => 'd-homework', 'cid' => $this->classroom_id]).'>点击前往</a> 查看',
             ];
         else {
             return [
                 'title' => '作业批阅完成',
                 'content' => '您的作业已经批阅完成！ <a href='.route('tasks.show', [$this->chapter, 'type' => 'd-homework']).'>点击前往</a> 查看',
             ];

         }

    }
}
