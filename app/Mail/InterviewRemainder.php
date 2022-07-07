<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewRemainder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $message_content;
    public function __construct($message_content)
    {
        $this->message_content = $message_content; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('site@hashroot.com','HashRoot One')
        ->subject('Interview Reminder')
        ->cc('shortlist@hashroot.com')
        ->html('<div style="font-family:calibri; max-width: 600px;"><br>
		'.$this->message_content.'
	 </div>');
    }
}
