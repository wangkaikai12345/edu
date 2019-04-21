<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;

class ReplyPolicy extends BasePolicy
{
    /**
     * 允许情景：回复者 || 话题作者
     *
     * @param User $user
     * @return bool
     */
    public function isAuthorOrTopicAuthor(User $user, Reply $reply)
    {
        $this->message(__('Only author or replier can do this.'));

        $bool = $user->id === $reply->user_id || $user->id === $reply->topic->user_id;

        return $this->trueOrThrow($bool);
    }
}
