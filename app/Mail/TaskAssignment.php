<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $maildata;
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("site@hashroot.com", "Autowelkin One Task Manager")
        ->subject('Autowelkin One - Task Assigned')
        ->html(' Hi '.$this->maildata["assignee"].', 
        <p>
            A new  '.$this->maildata["period_text"].' task has been assigned by '.$this->maildata["task_creator"].' on '.$this->maildata["date_created"].'
            <h4>Task : </h4> '.$this->maildata["title"].'
            <h4>Task in detail : </h4><p>'.$this->maildata["body"].'</p>
            '.$this->maildata["date_text"].'
        </p>');
    }
}
