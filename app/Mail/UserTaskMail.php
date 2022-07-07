<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         $task_data=$this->mail_data ;
   
         foreach($task_data as $data){
            $date_text	=	"";
            switch ($data->period) {
                case 'ONE':$period_text		=	"one time ";
                                 $date = date('d F Y',strtotime($data->date));
                                 $date_text	= $date;
                    break;
                case 'DAY':$period_text		=	"daily";# code...
                    break;
                case 'WEEK':$period_text	=	"weekly";# code...
                    break;
                case 'MONTH':$period_text  = "Monthly";# code...
                    break;
                default:$period_text		   ="not specified";
                    break;
            }
           
            $task_creator		=	user::find($data->creator_id)->fullname;
            $assignee			=	user::find($data->assign_to)->fullname;
            $subject	        =  "HashRoot One - Task Assigned";
            $date_created 		=  date("d M Y h:i:sa");
            $title=$data->title;
            $body=$data->body;

           //User::find($data->assign_to)->email //to address
            return $this->to(User::find($data->assign_to)->email)->from("site@hashroot.com", "HashRoot One Task Manager")
            ->subject($subject)
            ->view('template.user-task-mail', compact(['task_creator','period_text','date_created','date_text','title','body','assignee']));
    

         }

    
    }
}
