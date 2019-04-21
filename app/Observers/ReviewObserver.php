<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    /**
     * 当添加版本评论时，同时为该教学版本、课程增加评论数；并计算评分平均值
     *
     * @param Review $review
     * @throws
     */
    public function saved(Review $review)
    {
        // 计算版本平均分
        $planRating = Review::where('plan_id', $review->plan_id)->avg('rating');

        // 计算课程平均分
        $courseRating = Review::where('course_id', $review->course_id)->avg('rating');

        \DB::transaction(function () use ($review, $planRating, $courseRating) {
            // 更新评论数
            $review->plan()->increment('reviews_count');
            $review->course()->increment('reviews_count');
            // 更新评分
            // TODO 这条语句无效：$review->plan()->update(['rating' => $planRating]);
            $plan = $review->plan;
            $plan->rating = $planRating;
            $plan->save();
            $review->course()->update(['rating' => $courseRating]);
        });
    }

    /**
     * 当版本评价删除时，同时为该教学版本、课程减少评论数，并重新计算评分平均值
     *
     * @param Review $review
     * @throws
     */
    public function deleted(Review $review)
    {
        // 计算版本平均分
        $planRating = Review::where('plan_id', $review->plan_id)->avg('rating');

        // 计算课程平均分
        $courseRating = Review::where('course_id', $review->course_id)->avg('rating');

        \DB::transaction(function () use ($review, $planRating, $courseRating) {
            // 更新评论数
            $review->plan()->decrement('reviews_count');
            $review->course()->decrement('reviews_count');
            // 更新评分
            $review->plan()->update(['rating' => $planRating]);
            $review->course()->update(['rating' => $courseRating]);
        });
    }
}