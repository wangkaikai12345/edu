<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    private $url;
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
    public function __construct(User $user, string $url, int $token, string $expiredAt)
    {
        $this->user = $user;
        $this->url = $url;
        $this->token = $token;
        $this->expiredAt = $expiredAt;

        $this->subject('【' . config('app.name') . '】 密码重置');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password', [
            'user' => $this->user,
            'url' => $this->url,
            'token' => $this->token,
            'expiredAt' => $this->expiredAt
        ]);
    }
}
