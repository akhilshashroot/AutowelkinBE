<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserTaskCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_data,$comment_data,$user_id)
    {
        $this->mail_data = $mail_data;
        $this->comment_data = $comment_data;
        $this->user_id = $user_id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $task_data= $this->mail_data;
        $comment_data= $this->comment_data;
        $user_id= $this->user_id;
        $comment=$comment_data[0]['comments'];
      
        foreach($task_data as $data){

            if($user_id==$data->creator_id){
                $Receiver			=	 User::find($data->assign_to)->fullname;
                $Sender				=	 User::find($data->creator_id)->fullname;
                $ReceiverEmail	 = User::find($data->assign_to)->email;
            }elseif($data->creator_id==1){
                $Receiver			=	"Anees T";
                $Sender				=	 User::find($data->assign_to)->fullname;
                $ReceiverEmail	 =	 "anees@hashroot.com";
            }elseif($data->creator_id==7){
                $Receiver			=	"Muneer Muhammad";
                $Sender				=	 User::find($data->assign_to)->fullname;
                $ReceiverEmail	 =	 "muneer@hashroot.com";
            }else{
                $Receiver			=	 User::find($data->creator_id)->fullname;
                $Sender				=	 User::find($data->assign_to)->fullname;
                $ReceiverEmail	 =	  User::find($data->creator_id)->email;
            }
    
    
            $subject	               =  "Hashroot One  Tasker - New comment activity by ".$Sender;
            $date_created 			=  date("d M Y h:i:sa");
            $title=$data->title;
           
           // $this->email->to($ReceiverEmail); @todo
            return $this->to($ReceiverEmail)->from("site@hashroot.com", "HashRoot One Task Manager")
            ->subject($subject)
            ->view('template.user-task-comment-mail', compact(['title','comment','Receiver']));
    
    
        }
  
    
    }
}
