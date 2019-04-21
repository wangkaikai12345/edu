<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class BatchSendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var array
     */
    private $userIds;
    /**
     * @var bool
     */
    private $all;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $content;

    /**
     * Create a new job instance.
     *
     * @param string $title 标题
     * @param string $content 内容
     * @param array|string $userIds = 用户ID数组 或 all
     * @param bool $all
     */
    public function __construct($title, $content, array $userIds, bool $all = false)
    {
        $this->userIds = $userIds;
        $this->all = $all;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notifyData = [
            'title' => $this->title,
            'content' => $this->content,
        ];

        // 由于消耗较大，损耗时间较大，那么将其推送到队列之中时，php执行时间过长，所以设置在本脚本执行时，不限制执行时间。
        set_time_limit(0);

        if ($this->all) {
            $users = User::select(['id'])->get();
            $users->each(function ($user) use ($notifyData) {
                $user->notify(new SystemNotification($notifyData));
            });
        }

        if ($this->userIds) {
            User::whereIn('id', $this->userIds)->get(['id'])->each(function ($user) use ($notifyData) {
                $user->notify(new SystemNotification($notifyData));
            });
        }
    }
}
