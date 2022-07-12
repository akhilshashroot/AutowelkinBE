<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $request;
    public function __construct($user,$request, $lv_type)
    {
        $this->user = $user;
        $this->request = $request;
        $this->lv_type= $lv_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  		
        $rqtype=$this->lv_type;
        $user_data=$this->user;
        $request_data= $this->request;
        $subject = $rqtype." Request : ".$user_data->fullname;	 
        if(isset($user_data->team)) {		
        $team_emails =$user_data->team->mail_ids;
        $team_emails = explode(",",  $team_emails );
        foreach ($team_emails as $key => $value) {
            $cc_a[] = $value;
        }
        }
        $cc_a[] = 'lijimol.vr@hashroot.com';
        $cc_a=array_filter($cc_a);
        $email4 = "lijimol.vr@hashroot.com";
       // dd(  $cc_a);
      //  $this->email->cc($cc_a);
        // $this->email->cc('hr@hashroot.com');
     
     
        return $this->to($email4)->from("site@hashroot.com", "Autowelkin One")
        ->cc( $cc_a)
        ->subject($subject)
        ->view('template.leave-request-mail', ['user_data' => $user_data,'request_data' =>$request_data, 'rqtype' =>$rqtype]);
    }
}
