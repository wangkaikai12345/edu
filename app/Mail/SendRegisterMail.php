<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegisterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var int
     */
    private $token;
    /**
     * @var string
     */
    private $expiredAt;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $url
     * @param int $token
     * @param string $expiredAt
     */
    public function __construct(int $token, string $expiredAt)
    {
        $this->token = $token;
        $this->expiredAt = $expiredAt;

        $this->subject('【' . config('app.name') . '】 用户注册');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.register', [
            'token' => $this->token,
            'expiredAt' => $this->expiredAt
        ]);
    }
}
