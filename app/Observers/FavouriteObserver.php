<?php

namespace App\Observers;

use App\Models\Favorite;
use App\Models\Log;
use App\Models\Note;
use App\Notifications\FollowNotification;

class FavouriteObserver
{
    /**
     * 监听收藏事件
     *
     * @param Follow $follow
     */
    public function created(Favorite $favorite)
    {
        if ($favorite->model_type == 'note') {
            Note::find($favorite->model_id)->increment('collection');
        }
    }

    public function deleted(Favorite $favorite)
    {
        if ($favorite->model_type == 'note') {
            Note::find($favorite->model_id)->decrement('collection');
        }
    }
}