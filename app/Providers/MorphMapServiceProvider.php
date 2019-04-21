<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 定义多态关联
        Relation::morphMap([
            'image'      => \App\Models\Image::class,
            'video'      => \App\Models\Video::class,
            'ppt'        => \App\Models\Ppt::class,
            'doc'        => \App\Models\Doc::class,
            'audio'      => \App\Models\Audio::class,
            'plan'       => \App\Models\Plan::class,
            'recharging' => \App\Models\Recharging::class,
            'course'     => \App\Models\Course::class,
            'topic'      => \App\Models\Topic::class,
            'note'       => \App\Models\Note::class,
            'test'       => \App\Models\Test::class,
            'text'       => \App\Models\Text::class,
            'classroom'  => \App\Models\Classroom::class,
            'post'       => \App\Models\Post::class,
            'question'  => \App\Models\Question::class,
            'paper'  => \App\Models\Paper::class,
            'homework'  => \App\Models\Homework::class,
            'zip'  => \App\Models\Zip::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
