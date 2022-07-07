<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin;

class InterviewMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$interviewer_emails,$interview_function)
    {
        $this->data = $data;
		$this->interviewer_emails =$interviewer_emails;
		$this->interview_function =$interview_function;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//dd($this->interview_function );
		$insert_a=$this->data ;
		$interviewer_emails=$this->interviewer_emails ;
        $message_content  = '<table>';
		if($this->interview_function ){
		$message_content .= '<tr><td><h4>Updated Interview Schedule</h4></td></tr>';

		}else{
		$message_content .= '<tr><td><h4>Scheduled Interviews</h4></td></tr>';

		}
		$message_content .= '<tr><td>Interview Date : '.$insert_a["exam_date"].'</td></tr>';
		$message_content .= '<tr><td>Designation : '.$insert_a["position"].'</td></tr>';
		$message_content .= '<tr><td>Candidate Name : '.$insert_a["candidate_name"].'</td></tr>';
		$message_content .= '<tr><td>Candidate Email : '.$insert_a["candidate_email"].'</td></tr>';
		$message_content .= '<tr><td>Candidate Phone : '.$insert_a["candidate_phone"].'</td></tr>';
		$message_content .= '<tr><td>Mode of Interview : '.$insert_a["mode"].'</td></tr>';
		if($insert_a['resume'] != null){
			$message_content .= '<tr><td><a href="https://one.hashroot.com/server/storage/app/public/Resume/'.$insert_a['resume'].'"> Download CV</a></span></td></tr>';
		}
		
		
		$message_content .= '</table>';

		if($this->interview_function ){
			$subject	   = "Updated Interview Schedule";
	
			}else{
				$subject	   = "Interview Scheduled";
	
			}
	
		switch($insert_a['status']) {
			case "open":
				//$this->email->to('hr@hashroot.com');
                 $to_address='hr@hashroot.com';
				break;
			case "1st interview scheduled":
				if( $insert_a['creator'] == 1){ 
					$creator_email = 'anees@hashroot.com'; 
			     //	$creator_email = 'akhil.s@hashroot.com';
				    $to_address= $creator_email.','.$interviewer_emails; 
					//$to_address='akhil.s@hashroot.com';
				}
				else if( $insert_a['creator']== 3 ||  $insert_a['creator'] == 9 ){
					$to_address= $interviewer_emails.',hr@hashroot.com'; // @tochange

				}
				else{
					$creator_email=Admin::find($insert_a['creator'])->email;
					$to_address= $creator_email; 
				}
				break;
			case "2nd interview scheduled":
				if($insert_a['creator'] == 1){
							$creator_email = 'anees@hashroot.com'; 
						//$creator_email = 'akhil.s@hashroot.com';
						$to_address= $creator_email.','.$interviewer_emails; 
						//$to_address='akhil.s@hashroot.com';
				}
				else if($insert_a['creator'] == 3 || $insert_a['creator']== 9){
					$to_address= $interviewer_emails.',hr@hashroot.com'; 
				}
				else{
					$creator_email=Admin::find($insert_a['creator'])->email;
					$to_address= $creator_email.',hr@hashroot.com';;
				}
				break;
			case "for review":
				$subject = "For Review ".$insert_a["candidate_name"];
				$message_content = "The candidate, ".$insert_a["candidate_name"]." interviewed on ".$insert_a["exam_date"]. " for the position ".$insert_a["position"]." ,is selected for your review. Please add your review after closely examining the interviewers' comments and salary expectation and change the status accordingly";
				if($insert_a['creator'] == 1){
						$creator_email = 'anees@hashroot.com'; 
						//$creator_email = 'akhil.s@hashroot.com';
						$to_address= $creator_email.','.$interviewer_emails; 

				}
				else if($insert_a['creator'] == 3 || $insert_a['creator'] == 9){
					$to_address= $interviewer_emails.',hr@hashroot.com'; 
				}
				else{
					$to_address= $creator_email.',hr@hashroot.com';;
					$creator_email=Admin::find($insert_a['creator'])->email;
					//$to_address= $creator_email.',akhil.s@hashroot.com';
				}
				break;
		}
		if($insert_a['status'] !="open"){
			$to_address=explode(',', $to_address);

		}
		return $this->to($to_address)->from("site@hashroot.com", "HashRoot One")
        ->subject($subject)
        ->html('<div style="font-family:calibri; max-width: 600px;"><br>
		'.$message_content.'
	 </div>');

	
    }
}
