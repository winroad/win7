<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user->email) // 送信元
            ->subject('登録承認') // メールタイトル
            ->view('auth.activateMail') // どのテンプレートを呼び出すか
            ->with(['token' => $this->user->email_verify_token,])
            ->with(['user' => $this->user]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
