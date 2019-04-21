<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace App\Traits;

use App\Enums\NoticeType;

trait NoticeTrait
{
    /**
     * 版本公告
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePlan($query)
    {
        return $query->where('type', NoticeType::PLAN);
    }

    /**
     * 官网公告
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWeb($query)
    {
        return $query->where('type', NoticeType::WEB);
    }

    /**
     * 后台公告
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin($query)
    {
        return $query->where('type', NoticeType::ADMIN);
    }

    /**
     * 正在展示的公告
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnShow($query)
    {
        return $query->where('started_at', '<', now())->where('ended_at', '>', now());
    }
}