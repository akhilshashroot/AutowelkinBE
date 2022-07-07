<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewRemainder;
use App\Mail\ZeroBreaktimeList;
use App\Mail\PromotionNotification;
use App\Mail\TaskDeadlineRemainder;
use App\Models\AttendanceLog;
use App\Models\WeeklyWorkingHour;
use App\Models\SettingHour;
use App\Models\Assignment;
use App\Mail\ForcePunchoutMail;
use Illuminate\Support\Facades\Log;

class DailyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info( "Daily cron start");
        /// start interview remainder ////
        $start = strtotime(date("Y-m-d 00:00:00"));
		$end  = strtotime(date("Y-m-d 23:59:59"));
		$current_date = strtotime('now');
		$current_date = date('d-m-Y', $current_date);
        $result = Exam::where('exam_date_str','>',$start)->where('exam_date_str','<',$end)->where('is_active',1)->get();
        if($result) {
            foreach ($result as $key => $value) {
                $message_content	=	"";
                $message_content = '<h2 style="text-align: center;">Today`s Interviews</h2>';
                $message_content .= '<table border="1">';
                $message_content .= '<tr>';
                $message_content .= '<td>Sl</td>';
                $message_content .= '<td>Date</td>';
                $message_content .= '<td>Candidate Name</td>';
                $message_content .= '<td>Candidate Position</td>';
                $message_content .= '<td>Candidate Email</td>';
                $message_content .= '<td>Candidate Phone</td>';
                $message_content .= '<td>Current Status</td>';
                $message_content .= '<td>Interviewer</td>';
                $message_content .= '<td>Mode of Interview</td>';
                $message_content .= '</tr>';
                $count = 1;
                $to_a = [];
                $message_content .= '<tr>';
                $message_content .= '<td>'.$count.'</td>';
                $message_content .= '<td>'.$value->exam_date.'</td>';
                $message_content .= '<td>'.$value->candidate_name.'</td>';
                $message_content .= '<td>'.$value->position.'</td>';
                $message_content .= '<td>'.$value->candidate_email.'</td>';
                $message_content .= '<td>'.$value->candidate_phone.'</td>';
                $message_content .= '<td>'.$value->status.'</td>';

                $examiner_name	=	"";
                if($value->examiners_details){
                    $unserCandidate		=	unserialize($value->examiners_details);
                    foreach ($unserCandidate as $key => $candidate) {
                        array_push($to_a,$candidate['email']);
                        //array_push($to_a,"renjith.kr@hashroot.com");
                        $candidatedetails = User::where('user_id',$candidate['user_id'])->first();
                        $examiner_name		.= $candidatedetails->fullname;
                        $examiner_name		.= "<br />";
                        
                    }
                }else{
                        array_push($to_a,"hr@hashroot.com");
                        $examiner_name		.= "Not assigned";
                }
                $message_content .= '<td>'.$examiner_name.'</td>';
                $message_content .= '<td>'.$value->mode.'</td>';
                $message_content .= '</tr>';
                $count++;
                $message_content .= '<table>';
                try {
                    Mail::to($to_a)->send(new InterviewRemainder($message_content));
                } catch (\Exception $e) {
                    Log::info( "Interview Remainder Mail:".$e->getMessage());
                }
            }
        }
        ////end interview remainder///
        ///send break time start///
        $yesterday		=	 date('d',strtotime("-1 days"));
        $date = date("Y-d-m", strtotime('now'));
		$start_date = date('Y-m-'.$yesterday.' 00:00:00');
		$end_date = date('Y-m-'.$yesterday.' 23:59:59');
		$start_date = strtotime($start_date);
		$end_date = strtotime($end_date);
        $users = AttendanceLog::with('user','user.team','user.department','user.designation')
        ->where('punchin','>=',$start_date)->where('punchin','<=',$end_date)->where('punchout','!=',"")
        ->where('total_break','<',30)
        ->get();
        $date = date($yesterday.'-m-Y');
        if(count($users)){
            $message_content = '';
			$count = 1;
			$team_name = '';
            foreach ($users as $key => $value) {
                if($team_name != $value->user->team->name){
					$team_name = $value->user->team->name;
					$count = 1;
					$message_content .= '</table>';

					$message_content .= '<h4 style="text-align: center">'.$team_name.'</h4>';
					$message_content .= '<table border="1" style="text-align:center; float: none; margin: auto;  width: 330px;">';
					$message_content .= '<thead>';
					$message_content .= '<tr>';
					$message_content .= '<td>No.</td>';
					$message_content .= '<td>Name</td>';
					$message_content .= '<td>Designation</td>';
					$message_content .= '</tr>';
					$message_content .= '</thead>';
					$message_content .= '<tbody>';
					$message_content .= '<tr>';
					$message_content .= '<td>'.$count.'</td>';
					$message_content .= '<td>'.$value->user->fullname.'</td>';
					$message_content .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$value->user->designation->designation.'</td>';
					$message_content .= '</tr>';
				} else{
					$message_content .= '<tr>';
					$message_content .= '<td>'.$count.'</td>';
					$message_content .= '<td>'.$value->user->fullname.'</td>';
					$message_content .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$value->user->designation->designation.'</td>';
					$message_content .= '</tr>';
				}
                $count++;
            }
            $send_to[0]="hr@hashroot.com";
			$send_to[1]="qa@hashroot.com";
            try {
                $subject = "Zero Break Time List - ".$date;
                Mail::to($send_to)->send(new ZeroBreaktimeList($message_content,$subject));
            } catch (\Exception $e) {
                Log::info( "Zero break time list:".$e->getMessage());
            }
        }
        ///end break time start///
        ///l1 promotion remainder///
        $date = date("Y-d-m", strtotime('now'));
		$start_date = date('Y-m-d 00:00:00', strtotime("-6 month"));
		$end_date = date('Y-m-d 23:59:00', strtotime("-6 month"));
        $start_date = strtotime($start_date);
		$end_date = strtotime($end_date);
        $l1_eng = User::with('team','department','designation','promotion')->where('desgn_id', 1)
        //->whereHas('promotion', function($q) {
        //    $q->where('user_id',null);
        //})
        ->where('date_of_join','>=', $start_date)->where('date_of_join','<=', $end_date)->get();
        if(count($l1_eng)){
            $message_content = '<h3 style="text-align: center">New Promotion list</h3>';
			$count = 1;
			$team_name = '';
            foreach ($l1_eng as $key => $value) {
                if($team_name != $value->team_name){
					$team_name = $value->team_name;
					$count = 1;
					$message_content .= '</table>';

					$message_content .= '<h4 style="text-align: center;">'.$team_name.'</h4>';
					$message_content .= '<table border="1" style="text-align:center; float: none; margin: auto; width: 100%;">';
					$message_content .= '<thead>';
					$message_content .= '<tr>';
					$message_content .= '<td>No.</td>';
					$message_content .= '<td>Name</td>';
					$message_content .= '<td>Designation</td>';
					$message_content .= '</tr>';
					$message_content .= '</thead>';
					$message_content .= '<tbody>';
					$message_content .= '<tr>';
					$message_content .= '<td>'.$count.'</td>';
					$message_content .= '<td>'.$value->fullname.'</td>';
					$message_content .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$value->designation.'</td>';
					$message_content .= '</tr>';


				}else{
					$message_content .= '<tr>';
					$message_content .= '<td>'.$count.'</td>';
					$message_content .= '<td>'.$value->fullname.'</td>';
					$message_content .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$value->designation.'</td>';
					$message_content .= '</tr>';
				}
                $pr_detail = new PromotionNotification;
                $pr_detail->user_id = $value->user_id;
                $pr_detail->fullname = $value->fullname;
                $pr_detail->save();
                $count++;
            }
            $date = date('d-m-Y');
			$message_content .= '</table>';
            
            /*$send_to[0]="anees@hashroot.com";
			$send_to[1]="sandeep@hashroot.com";
			$send_to[2]="sachin@hashroot.com";
			$send_to[3]="krishnaprasad@hashroot.com";
			$send_to[4]="qa@hashroot.com";
			$send_to[5]="requests@hashroot.com";*/
            $send_to[0]="lijimol.vr@hashroot.com";
            try {
                $subject = "Promotion list - ".$date;
                Mail::to($send_to)->send(new PromotionNotification($message_content,$subject));
            } catch (\Exception $e) {
                Log::info( "Promotion list:".$e->getMessage());
            }
        }
        ///l1 promotion remainder end///
        ///Task remainder mail start///
        $taskList = Assignment::where('status',0)->get();
        foreach ($taskList as $key => $value) {
            $updatetask = Assignment::where('asgnmnt_id',$value->asgnmnt_id)->first();
            switch ($value->period) {
                case 'Weekly':
							
                    if($value->date<date('w') || ($value->date==6 && date('w') ==0)){
                        $updatetask->status = 0;
                        $status		=	$updatetask->save();
                    }
                break;
                case 'Monthly':
                    $lastDayOfTheMonth =	date('d',strtotime('last day of this month'));
                    $current_day			=	 date('d');
                    if($lastDayOfTheMonth==$current_day){
                        $updatetask->status = 0;
                        $status		=	$updatetask->save();
                    }
                break;
                case 'ONE':
                    $taskData	=	$value;
                    $assignee	=	User::where('id',$taskData->assign_to)->first();
                    if($value->date==date("Y-m-d",strtotime('tomorrow'))){
                        $taskData->message 	=	" Hi ".$assignee->fullname."<p>This is a reminder from HashRoot One regarding the deadline associated with the task assigned to you via PE. You have one day left to complete the task </p>";
                        $this->notifyUpdater($taskData);
                    }
                    if($value->date==date("Y-m-d",strtotime('yesterday'))){
                        $assigner	=	User::where('id',$taskData->creator_id)->first();
                        $taskData->message 	=	"Hi ".$assignee->fullname."<p>Please note that you haven't completed the task assigned to you, by  ".$assigner->fullname." within the deadline</p>";
                        $this->notifyUpdater($taskData);
                    }
                break;
            default:$flag="nothing done";
            break;
            }
        }
        ///Task remainder mail end///
    }
    public function notifyUpdater($taskData){
        if(11>date('H') || date('H')>13){
            return;
        }
        if($taskData->creator_id==1){
            $taskData->assigner	=	'anees@hashroot.com';
        }elseif($taskData->creator_id==7){
            $taskData->assigner	=	'muneer@hashroot.com';
        }else{
            $emp_details = User::where('user_id',$taskData->creator_id)->first();
            $taskData->assigner	=	$emp_details->email;
        }
        $emp_details = User::where('user_id',$taskData->assign_to)->first();
            $taskData->assignee = $emp_details->email;
        
            try {
                //Mail::to($taskData->assignee)->send(new TaskDeadlineRemainder($taskData->message,$taskData->title));
                Mail::to('lijimol.vr@hashroot.com')->send(new TaskDeadlineRemainder($taskData->message,$taskData->title));
            } catch (\Exception $e) {
                Log::info( "Task Deadline Remainder Mail:".$e->getMessage());
            }
    }
}
