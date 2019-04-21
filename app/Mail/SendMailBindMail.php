<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailBindMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $expiredAt;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $url
     * @param string $expiredAt
     */
    public function __construct(User $user, string $url, string $expiredAt)
    {
        $this->user = $user;
        $this->url = $url;
        $this->expiredAt = $expiredAt;

        $this->subject('【' . config('app.name') . '】 邮箱绑定验证');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.bind', [
            'user' => $this->user,
            'url' => $this->url,
            'expiredAt' => $this->expiredAt
        ]);
    }
}
