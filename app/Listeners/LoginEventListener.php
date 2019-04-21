<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class LoginEventListener implements ShouldQueue
{
    use SerializesModels;

    /**
     * Handle the event.
     *
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $user = $event->user;
        $user->last_logined_at = $event->loginTime;
        $user->last_logined_ip = $event->loginIp;
        $user->save();
    }
}
