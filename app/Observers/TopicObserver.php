<?php

namespace App\Observers;

use App\Models\Follow;
use App\Models\Topic;
use App\Notifications\NewTopicNotification;

class TopicObserver
{
    /**
     * TODO 为粉丝发送消息通知可以移入队列之中
     * 话题创建
     *
     * @param Topic $topic
     */
    public function created(Topic $topic)
    {
        $topic->plan()->increment('topics_count');

        // 粉丝发送通知
        $fans = Follow::where('user_id', $topic->user_id)->get();

        if ($fans->count()) {
            foreach ($fans as $fan) {
                // 发送通知
                $fan->follow->notify(new NewTopicNotification($topic));
            }
        }
    }

    /**
     * 话题删除
     *
     * @param Topic $topic
     */
    public function deleted(Topic $topic)
    {
        $topic->plan()->decrement('topics_count');
    }
}
