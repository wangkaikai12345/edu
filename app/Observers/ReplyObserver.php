<?php

namespace App\Observers;

use App\Models\Reply;

class ReplyObserver
{
    /**
     * 回复
     *
     * @param Reply $reply
     */
    public function created(Reply $reply)
    {
        $reply->topic()->increment('replies_count');
        $reply->topic()->increment('hit_count');
    }

    /**
     * 删除
     *
     * @param Reply $reply
     */
    public function deleted(Reply $reply)
    {
        $reply->topic()->decrement('replies_count');
    }
}