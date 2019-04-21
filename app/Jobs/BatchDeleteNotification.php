<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BatchDeleteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var array
     */
    private $whereBetween;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $whereBetween)
    {
        $this->whereBetween = $whereBetween;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 由于消耗较大，损耗时间较大，那么将其推送到队列之中时，php执行时间过长，所以设置在本脚本执行时，不限制执行时间。
        set_time_limit(0);

        $notifications = Notification::whereBetween('created_at', $this->whereBetween)
            ->where('type', 'App\Notifications\SystemNotification')
            ->get(['id', 'read_at', 'notifiable_id', 'notifiable_type']);

        $notifications->each(function ($item) {
            if (!$item->read_at) {
                $item->notifiable()->decrement('new_notifications_count');
            }
            $item->delete();
        });
    }
}
