<?php

namespace App\Listeners;

use App\Events\PermissionCacheClearEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class PermissionCacheClearEventListener implements ShouldQueue
{
    use SerializesModels;

    /**
     * Handle the event.
     *
     * @param  PermissionCacheClearEvent  $event
     * @return void
     */
    public function handle(PermissionCacheClearEvent $event)
    {
        app()['cache']->forget('spatie.permission.cache');
    }
}
