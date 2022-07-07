<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ZeroBreaktimeList extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $message_content;
    public $subject;
    public function __construct($message_content,$subject)
    {
        $this->message_content = $message_content;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('site@hashroot.com','HashRoot One')
        ->subject($this->subject)
        ->cc('shortlist@hashroot.com')
        ->html('<div style="font-family:calibri; max-width: 600px;"><br>
		'.$this->message_content.'
	 </div>');
    }
}
