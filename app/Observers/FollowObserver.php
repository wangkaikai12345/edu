<?php

namespace App\Observers;

use App\Models\Follow;
use App\Notifications\FollowNotification;

class FollowObserver
{
    /**
     * 监听关注事件
     *
     * @param Follow $follow
     */
    public function created(Follow $follow)
    {
        // 查询是否相互关注
        $where = ['user_id' => $follow->follow_id, 'follow_id' => $follow->user_id];
        if (Follow::where($where)->exists()) {
            $follow->update(['is_pair' => 1]);
            Follow::where($where)->update(['is_pair' => 1]);
        }

        // 发送消息提醒
        $follow->follow->notify(new FollowNotification($follow));
    }

    /**
     * 监听取关事件
     *
     * @param Follow $follow
     */
    public function deleted(Follow $follow)
    {
        // 查询是否相互关注
        if ($follow->is_pair) {
            $where = ['user_id' => $follow->follow_id, 'follow_id' => $follow->user_id];
            Follow::where($where)->update(['is_pair' => 0]);
            $follow->update(['is_pair' => 0]);
        }
    }
}