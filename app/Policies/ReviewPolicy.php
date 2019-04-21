<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy extends BasePolicy
{
    /**
     * 允许情景：作者
     *
     * @param User $user
     * @param Review $review
     * @return bool
     */
    public function isAuthor(User $user, Review $review)
    {
        return $this->trueOrThrow($user->id === $review->user_id);
    }
}
