<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestApprove extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $request;
    public $request_type;
    public function __construct($request,$request_type)
    {
        $this->request = $request;
        $this->request_type = $request_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("site@hashroot.com", "Autowelkin One")
        ->subject('Autowelkin One '.$this->request_type)
        ->html('Hi  , <br/><p><b> Requested date '.date('d M Y',$this->request->lv_aply_date).'</b></p><p>Your '.$this->request_type.' request has been approved</p>');
    }
}
