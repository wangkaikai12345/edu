<?php

namespace App\Events;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var Carbon
     */
    public $loginTime;
    /**
     * @var string
     */
    public $loginIp;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Carbon $loginTime, string $loginIp)
    {
        $this->user = $user;
        $this->loginTime = $loginTime;
        $this->loginIp = $loginIp;
    }
}
