<?php

namespace App\Traits;

trait NotCopyTrait
{
    /**
     * 非复制数据
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCopy($query)
    {
        return $query->where('copy_id', '=', 0);
    }
}