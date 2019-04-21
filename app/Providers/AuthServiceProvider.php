<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 课程
        \App\Models\Course::class => \App\Policies\CoursePolicy::class,
        // 版本
        \App\Models\Plan::class => \App\Policies\PlanPolicy::class,
        // 话题
        \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        // 笔记
        \App\Models\Note::class => \App\Policies\NotePolicy::class,
        // 回复
        \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
        // 版本评价
        \App\Models\Review::class => \App\Policies\ReviewPolicy::class,
        // 用户
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        // 评价
        \App\Models\Review::class => \App\Policies\ReviewPolicy::class,
        // 班级
        \App\Models\Classroom::class => \App\Policies\ClassroomPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
