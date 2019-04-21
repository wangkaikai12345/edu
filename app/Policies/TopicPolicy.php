<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;

class TopicPolicy extends BasePolicy
{
    /**
     * 允许情景：作者
     *
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function isAuthor(User $user, Topic $topic)
    {
        return $this->trueOrThrow($user->id === $topic->user_id);
    }
}
