<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SkillUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $details;
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("site@hashroot.com", "HashRoot One Review Request")
        ->subject('Review Request from '.$this->details->user->fullname)
        ->html('<div style="font-family:calibri; max-width: 300px;"> Employee name : '.$this->details->user->fullname.'<br /> Requested for Review :	<b>'.$this->details->skill_name.' </b><br /> Request time : '.date("d F Y h.i A").'</div>');
    }
}
