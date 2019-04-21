<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 课程
        \App\Models\Course::observe(\App\Observers\CourseObserver::class);
        // 版本
        \App\Models\Plan::observe(\App\Observers\PlanObserver::class);
        // 订单
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        // 交易记录
        \App\Models\Trade::observe(\App\Observers\TradeObserver::class);
        // 退款
        \App\Models\Refund::observe(\App\Observers\RefundObserver::class);
        // 笔记
        \App\Models\Note::observe(\App\Observers\NoteObserver::class);
        // 版本评价
        \App\Models\Review::observe(\App\Observers\ReviewObserver::class);
        // 版本成员
        \App\Models\PlanMember::observe(\App\Observers\PlanMemberObserver::class);
        // 话题
        \App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        // 回复
        \App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
        // 关注
        \App\Models\Follow::observe(\App\Observers\FollowObserver::class);
        // 任务结果
        \App\Models\TaskResult::observe(\App\Observers\TaskResultObserver::class);
        // 系统配置
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        // 任务
        \App\Models\Task::observe(\App\Observers\TaskObserver::class);
        // 私信提醒
        \App\Models\MessageNotification::observe(\App\Observers\MessageNotificationObserver::class);
        // 班级
        \App\Models\Classroom::observe(\App\Observers\ClassroomObserver::class);
        // 班级课程
        \App\Models\ClassroomCourse::observe(\App\Observers\ClassroomCourseObserver::class);
        // 班级学员
        \App\Models\ClassroomMember::observe(\App\Observers\ClassroomMemberObserver::class);
        // 收藏
        \App\Models\Favorite::observe(\App\Observers\FavouriteObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
