<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy extends BasePolicy
{
    /**
     * 允许情景：作者
     *
     * @param User $user
     * @param Note $note
     * @return bool
     */
    public function isAuthor(User $user, Note $note)
    {
        return $this->trueOrThrow($user->id === $note->user_id);
    }
}
