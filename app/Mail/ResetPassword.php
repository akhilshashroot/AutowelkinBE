<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $name;
    public $token;

    public function __construct($name, $token, $url)
    {
        $this->name = $name;
        $this->token = $token;
        $this->url =$url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user['name'] = $this->name;
        $user['token'] = $this->token;
        $user['url'] = $this->url;

        return $this->from("site@hashroot.com", "Autowelkin One")
        ->subject('Autowelkin One Password Reset Link')
        ->view('template.reset-password', ['user' => $user]);
    }
}