<?php

namespace App\Providers;

use App\Events\CreateLocalVideoTaskEvent;
use App\Listeners\CreateLocalVideoTaskListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\LoginEvent' => [
            'App\Listeners\LoginEventListener',
        ],
        'App\Events\CourseViewEvent' => [
            'App\Listeners\CourseViewListener'
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\Weixin\WeixinExtendSocialite@handle'
        ],
        'App\Events\PermissionCacheClearEvent' => [
            'App\Listeners\PermissionCacheClearEventListener'
        ],
        CreateLocalVideoTaskEvent::class => [
            CreateLocalVideoTaskListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
