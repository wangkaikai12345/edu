<?php

namespace App\Observers;

use App\Models\Note;

class NoteObserver
{
    /**
     * 创建
     *
     * @param Note $note
     * @throws
     */
    public function created(Note $note)
    {
        \DB::transaction(function () use ($note) {
            // 版本笔记个数递增
            $note->plan()->increment('notes_count');
            // 课程笔记个数递增
            $note->course()->increment('notes_count');
        });
    }

    /**
     * 删除
     *
     * @param Note $note
     * @throws
     */
    public function deleted(Note $note)
    {
        \DB::transaction(function () use ($note) {
            // 版本笔记个数递减
            $note->plan()->decrement('notes_count');
            // 版本笔记个数递减
            $note->course()->decrement('notes_count');
        });
    }
}