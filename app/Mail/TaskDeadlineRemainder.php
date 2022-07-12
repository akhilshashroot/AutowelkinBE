<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskDeadlineRemainder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $message;
    public $title;
    public function __construct($message,$title)
    {
        $this->message = $message;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('site@hashroot.com',' Autowelkin One Task Manager')
        ->subject('PE Tasker - Deadline Reminder')
        //->cc('shortlist@hashroot.com')
        ->html($this->message."<br /> Task : ".$this->title);
    }
}
