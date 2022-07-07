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

class SimpleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simple:cron';

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
        Log::info( "Force punchout cron start");
        $Users = AttendanceLog::with('user')->where('att_status',0)->get();
        foreach($Users as $user){
            $totaltime	=strtotime('now')-$user['punchin'];
			$TotalHrs	=round($totaltime/3600);
            if($TotalHrs>=12){
                $work_reports		=	unserialize($user['work_report']);
				$lastActivityTime	=	0;
                if($work_reports) {
                    foreach($work_reports as $report){
                        if(isset($report['time'])) {
                            if($lastActivityTime < $report['time']){
                                $lastActivityTime 	= $report['time'];
                            }
                        }
                    } 
                }

                $totalbr=0;
				//if(!isset($break) || trim($break) === ''){
                    if(isset($user['break'])) {
                        $break=unserialize($user['break']);
                    if($break) {
                        if(count($break)>0){
                            foreach($break as $br){
                                if(!empty($br['off']) && !empty($br['on'])){
                                        $totalbr	=	($br['off']-$br['on'])+$totalbr;
                                    }
                            }
                        }
                    }
                    }
				//}
                $lastActivityTimeDifference	 =	strtotime('now')-$lastActivityTime;
				$lastActivityRealTime		   =  round($lastActivityTimeDifference/3600);
                if($lastActivityTime<=0){
					$punchout_details = AttendanceLog::where('att_id',$user['att_id'])->first();
					$punchout_details->punchout			=	strtotime('now');
					$punchout_details->worked_time	  =	 (8*3600)-$totalbr;
					$punchout_details->total_break		=	$totalbr;
					$punchout_details->att_status		 =	1;
                    $update = $punchout_details->save();
				}elseif($lastActivityRealTime>3){
                    $punchout_details = AttendanceLog::where('att_id',$user['att_id'])->first();
					$punchout_details->punchout			=	$lastActivityTime;
					$punchout_details->worked_time    =	 ($lastActivityTime-$user['punchin'])-$totalbr;
					$punchout_details->total_break		=	$totalbr;
					$punchout_details->att_status		 =	 1;
                    $update = $punchout_details->save();
				}
                $tot_wrkd_time 			= $punchout_details->worked_time;
				$lastsun 				= strtotime('last Monday');
				$today   				= strtotime('now');
				$get_wrkd_hrs 			= WeeklyWorkingHour::with('user')->where('user_id',$user['user_id'])->where('date','>=', $lastsun)
                ->orderBy('wrk_id','desc')->first();
				$get_calcs = SettingHour::get();
				$fixed_pending_hrs 		= $get_calcs[0]->pending_calc ;
				$time_conv 				= explode(':',$fixed_pending_hrs);
				$fix_pend_minutes 		= 148500;
                if(is_array($get_wrkd_hrs)) {
                    if(count($get_wrkd_hrs) > 0){
                        $w_id					= $get_wrkd_hrs->wrk_id;
                        $sum 					= $get_wrkd_hrs->hrs_worked + ($tot_wrkd_time) ;
                        $get_wrkd_hrs->hrs_worked	= round($sum);    
                        $get_wrkd_hrs->pending_hrs	= round($get_wrkd_hrs->pending_hrs - $tot_wrkd_time);
                        $upd = $get_wrkd_hrs->save();
                
                    }
                }else{
                    $sum 					= $tot_wrkd_time;
                    $wrkd_hrs = new WeeklyWorkingHour;
                    $wrkd_hrs->user_id = $user['user_id'];
                    $wrkd_hrs->hrs_worked = round($sum) ;
                    $wrkd_hrs->pending_hrs = round($fix_pend_minutes - $sum) ;
                    $wrkd_hrs->date = strtotime('now') ;
                    $wrkd_hrs->save();
                }
                try {
                    $subject ="HashRoot One - Force Punch Out -".$user['fullname'];
                    Mail::to($user->user->email)->send(new ForcePunchoutMail($subject));
                } catch (\Exception $e) {
                    Log::info( "ForcePunchout mail:".$e->getMessage());
                }
            }
        }

    }
    
    
}
