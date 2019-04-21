<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace App\Traits;

trait ModelHasRecommendTrait
{
    /**
     * 推荐
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecommend($query)
    {
        return $query->where('is_recommended', 1);
    }

    /**
     * 非推荐
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotRecommend($query)
    {
        return $query->where('is_recommended', 0);
    }

    /**
     * 推荐排序
     */
    public function scopeSortBySeq($query, $sort = 'desc')
    {
        return $query->orderBy('recommended_seq', $sort);
    }
}