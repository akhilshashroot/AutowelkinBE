<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\WFHManage;
use App\Models\Requst;
use App\Models\WFHBreak;
use App\Models\WeeklyWorkingHour;
use App\Models\SettingHour;
use App\Models\Team;
use App\Models\WeeklyActivity;
use App\Models\DeskImage;
use App\Models\WeeklyData;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivityDetails;
use App\Models\MonthlyActivity;
use App\Models\RepeatMonthlyData;
use App\Models\WorkReport;
use App\Models\TicketDetails;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$user_id)
    {
        //$user_id = $request->user_id;
        $crntMonth = date('m-Y');
        //$crntMonth = '09-2020';
        $shiftcount=0;
        $daily_logs = array();
        $attendancelog = AttendanceLog::with('user','location','wfhbreak')->where('user_id',$user_id)->where('punchin_date', 'LIKE', "%{$crntMonth}%")->get();
        foreach($attendancelog as $log) {
            $cday=date('j',$log->punchin);
            @$daily_log['date'] = $cday;
            @$daily_log['loc_title'] = $log->loc_title;
            if($log->work_loc == '0') {
                @$daily_log['work_loc'] = 'Regular';
            }else if($log->work_loc == '1') {
                @$daily_log['work_loc'] = 'Swap Shift';
            }else if($log->work_loc == '2') {
                @$daily_log['work_loc'] = 'Home Login';
            }else if($log->work_loc == '3') {
                @$daily_log['work_loc'] = 'Extra Hours';
            } else {
                @$daily_log['work_loc'] = 'Project';
            }
			@$daily_log['punchin_time']=date('d MY  h:i a',$log->punchin);
            if($log->punchout == null) {
                @$daily_log['punchout_time'] = "Haven't Punched Out";
            } else {
                @$daily_log['punchout_time'] = date('d MY  h:i a',$log->punchout);
            }
			//@$daily_log['punchout_time']=date('d MY  h:i a',$log->punchout) ?: "Haven't Punched Out";
			@$daily_log['punchin_ip']=$log->punchin_ip ?: "--";
			@$daily_log['punchout_ip']=$log->punchout_ip ?: "-- ";
			if($log->punchout && $log->punchout_ip==""){
				$daily_log['punchout_ip'] = "Force Punchout";
			}
			@$daily_log['worked_time']=$this->GetRealTime($log->worked_time);
			@$daily_log['total_break']=$this->GetRealTime($log->total_break);
			@$daily_log['break_times']= $this->get_breaks($log->break);
            $break_detail = unserialize($log->break);
            if($break_detail) {
                foreach ($break_detail as $row) {
                    if(array_key_exists('on', $row)){
                        @$daily_log['break_status'] = 'on';
                    }
                    if(array_key_exists('off', $row)){
                        @$daily_log['break_status'] = 'off';
                    }
                }
            } else {
                @$daily_log['break_status'] = 'off';
            }
            
			@$daily_log['idle_time']=$this->GetRealTime($log->total_time);
            $daily_logs['log'][]=$daily_log;
            //array_push( $daily_logs, $daily_log);

			if($log->worked_time >10800){
				$shiftcount=$shiftcount+1;
			}
        }
        return response()->json([
            'data' => $daily_logs,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function punchin(Request $request) {
        $work_loc = $request->work_loc;
        $casual_leave = $request->casual_leave;
        $user_id = $request->user_id;
        
        if(!$casual_leave){
			$casual_leave = false;
		}else{
			$casual_leave = true;
		}
        $user_details = User::with('department','performance','team')->where('id',$user_id)->first();
        $date_of_join = $user_details->date_of_join;
        $getLeaveResetDate = User::getLeaveResetDate($date_of_join);
        $wfh_permission = $user_details->no_wfh;
        if(($work_loc == 0) || ($work_loc == 1) || ($work_loc == 3)){

			$ip_a[] = '103.61.12.146';
			$ip_a[] = '202.83.55.157';
			$ip_a[] = '202.88.227.250';
			$ip_a[] = '69.12.78.213';

			if(!in_array($_SERVER['REMOTE_ADDR'], $ip_a)){
                return response()->json([
                    'status' => false,
                    'message' => 'IP Mismatch! Action Restricted'
                ], 200);	
			}
		}
        if($wfh_permission == 1){
			if($work_loc == 2 && $casual_leave == false){
				
				$total_wofhm = User::NoofWFH($user_id, $getLeaveResetDate);
                $total_wofhm = $total_wofhm[0]->total;
				$total_wofhm = intval($total_wofhm);
				if($total_wofhm > $user_details->no_wfh){
                    return response()->json([
                        'status' => false,
                        'message' => 'home login exceeded'
                    ], 200);
				}
			}

			if($work_loc == 2 && $casual_leave == true){
                $total_wofhm_result = $this->get_wfh_cl_count($user_id);
                if($total_wofhm_result) {
                    $total_wofhm = $total_wofhm_result->wfh_count;
                } else {
                    $total_wofhm = 1;
                }
				if($total_wofhm % 2 != 0){
                    if($total_wofhm_result) {
                        $wfh_count = intval($total_wofhm) + 1;
                        $total_wofhm_result->wfh_count = $wfh_count;
                        $total_wofhm_result->user_id = $user_id;
                        $result = $total_wofhm_result->save();
                    } else {
                        $wfhmanage = new WFHManage;
                        $wfhmanage->user_id= $user_id;
                        $wfhmanage->wfh_count = 2;
                        $result = $wfhmanage->save();
                    }
				}else{
                    $casual_leave_no = Requst::where('user_id',$user_id)->where('lv_type',1)->where('lv_status',1)
                    ->where('lv_date','>=',$getLeaveResetDate)->sum('lv_no');
                    $leave = new Requst;
                    if($casual_leave_no >= 6){
						$leave->lv_type = 4;
					}else{
						$leave->lv_type = 1;
					}
					$leave->lv_date = strtotime('now');
					$leave->lv_no = 1;
					$leave->user_id = $user_id;
					$leave->lv_purpose = 'Home login exceeded';
					$leave->lv_aply_date = strtotime('now');
					$leave->lv_date_to = strtotime('now');
					$leave->lv_status = 1;
					$leave->appr_person = 'System';
                    $result1 = $leave->save();
                    if($result1) {
                        $check_wfh = $this->get_wfh_cl_count($user_id, 'model');
                        if($check_wfh) {
                            $wfh_count = intval($check_wfh->wfh_count) + 1;
                            $check_wfh->wfh_count = $wfh_count;
                            $check_wfh->user_id = $user_id;
                            $result = $check_wfh->save();
                        } else {
                            $wfhmanage = new WFHManage;
                            $wfhmanage->user_id= $user_id;
                            $wfhmanage->wfh_count = 2;
                            $result = $wfhmanage->save();
                        }
                    }
				}
			}
		}
        $att_det = AttendanceLog::where('user_id',$user_id)->where('att_status',0)->get();
        if($att_det->count()) {
            $time = $att_det[0]['punchin'];
            return response()->json([
                'time' => date('d M Y h:i a',$time),
                'message' => 'Success'
            ], 200);
        } else {
            $attendancelog = new AttendanceLog;
            $attendancelog->user_id = $user_id;
            $attendancelog->punchin = strtotime('now');
            $attendancelog->punchin_date = date('d-m-Y');
            $attendancelog->work_loc = $work_loc;
            $attendancelog->punchin_ip = $_SERVER['REMOTE_ADDR'];
            $attendancelog->att_status = 0;
            $punchin_result = $attendancelog->save();
            if(($work_loc == 2) && ($attendancelog->att_id)) {
                $wfhbreak = new WFHBreak;
                $wfhbreak->p_id = $attendancelog->att_id;
                $wfhbreak->user_id = $user_id;
                $wfhbreak_result = $wfhbreak->save();
            }
            return response()->json([
                'time' => date('d M Y h:i a'),
                'message' => 'Success'
            ], 200);
        }
    }
    //function convert to real time()
	function GetRealTime($sec){
		$minte=round((int)$sec/60);
		$min=($minte%60);
		$hrs=(($minte-$min)/60);
		$min=abs($min);
		if($min<10){
			$min="0".$min;
		}
		$realtime=" ".($hrs)." : ".$min."";
		return $realtime;
	}
    public function breaktime(Request $request) {
        $breakstatus = $request->breakstatus;
        $user_id = $request->user_id;
        $ip = $_SERVER['REMOTE_ADDR'];
        $last_punching_details = AttendanceLog::where('user_id', $user_id)->orderBy('att_id', 'desc')->first();
        $work_loc = $last_punching_details->work_loc;
        if(($work_loc == 0) || ($work_loc == 1) || ($work_loc == 3)){
            $ip_a[] = '50.7.126.205';
			$ip_a[] = '45.58.123.5';
			$ip_a[] = '139.162.214.101';
			$ip_a[] = '202.88.227.250';
			$ip_a[] = '202.83.55.157';
			$ip_a[] = '69.12.78.213';
			$ip_a[] = '103.61.12.146';
			$ip_a[] = '199.195.142.172';
			$ip_a[] = '54.148.154.57';
			$ip_a[] = '69.12.84.231';
			$ip_a[] = '45.58.123.1';
			$ip_a[] = '45.58.123.2';
			$ip_a[] = '45.58.123.3';
			$ip_a[] = '45.58.123.4';
			$ip_a[] = '45.58.123.5';
			$ip_a[] = '45.58.123.6';
			$ip_a[] = '45.58.123.7';
			$ip_a[] = '45.58.123.8';
			$ip_a[] = '45.58.123.9';
			$ip_a[] = '45.58.123.10';
			$ip_a[] = '45.58.123.11';
			$ip_a[] = '45.58.123.12';
			$ip_a[] = '45.58.123.13';
			$ip_a[] = '45.58.123.14';
			$ip_a[] = '45.58.123.15';
			$ip_a[] = '45.58.123.16';
			$ip_a[] = '104.145.233.19';
			$ip_a[] = '104.149.94.163';
			$ip_a[] = '199.43.207.163';

            if(!in_array($ip, $ip_a)){
                return response()->json([
                    'status' => false,
                    'message' => 'Action Restricted'
                ], 200);				
			}
        }
        $get_punch_att = AttendanceLog::where('user_id',$user_id)->where('att_status',0)
        ->orderBy('att_id','desc')->first();
        $at_id = $get_punch_att->att_id;
        if($breakstatus == 'off') {
            if((empty($get_punch_att->punchout)) && (!empty($get_punch_att->punchin)))  {
                if(empty($get_punch_att->break)) {
                    $breaks[0]['on'] = strtotime('now');
                    $get_punch_att->break = serialize($breaks);
                    $get_punch_att->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'Success'
                    ], 200);
                } else {
                    $unserialized = unserialize($get_punch_att->break);
                    $count_breaks = count($unserialized);
                    if(array_key_exists('off',$unserialized[$count_breaks-1])){
                        $unserialized[$count_breaks]['on'] = strtotime('now');
					    $get_punch_att->break = serialize($unserialized); 
                        $get_punch_att->save();
                    } else {
                        $unserialized[$count_breaks-1]['off'] = strtotime('now');
                        $get_punch_att->break = serialize($unserialized);
                        $get_punch_att->save();
                        if($user_id == 430){
                        }
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Success'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Punch in time and break time mismatch!'
                ], 200);
            }
        } else {
            if((empty($get_punch_att->punchout)) && (!empty($get_punch_att->punchin)))  {
                $unser  = unserialize($get_punch_att->break);
				$count  = count($unser);
                if((array_key_exists('on',$unser[$count-1]))&& !(array_key_exists('off',$unser[$count-1]))){
                    $unser[$count-1]['off']	 = strtotime('now');
					$get_punch_att->break = serialize($unser);
                    $get_punch_att->save();
                    $break_det = AttendanceLog::where('user_id',$user_id)->where('att_id',$at_id)->first();
                    $unser = unserialize($break_det->break);
                }
                $total_break_display ='';
			    $total_diff = 0;
                $att_break  = AttendanceLog::where('user_id',$user_id)->where('att_status',0)
                ->orderBy('att_id','desc')->first();
                $break_details = unserialize($att_break->break);
			    $count_break = count($break_details);
                if($count_break>0){
                    foreach($break_details as $row){							
                        if(array_key_exists('off',$row) && array_key_exists('on',$row)){								
                            $diff               = $row['off'] - $row['on'];								
                            $total_diff         = $total_diff + $diff;	
                        }	
                    }
                    $tdiff 				  = $total_diff;//in seconds
					$total_diff1		  = $total_diff / 60;//in min
					$brk_rem 		      = $total_diff1 % 60; 
					$brk_hrs              = $total_diff1 - $brk_rem;
					$tot_brk_hrs          = $brk_hrs/60;// in hrs
					$total_break_hours    = round($tot_brk_hrs)."Hrs ".round($brk_rem)." min";
					$att_break->total_break  = $tdiff;
                    $att_break->save();
                    $total_break_display  = "Total Break Taken : ".$total_break_hours;
                    return response()->json([
                        'time' => $total_break_display,
                        'message' => 'Success'
                    ], 200);
                }
            }
        }
    }
    public function punchout(Request $request) {
        $user_id = $request->user_id;
        $at_data = AttendanceLog::where('user_id',$user_id)->where('att_status',0)->first();
        if(empty($at_data)) {
            return response()->json([
                'status' => false,
                'message' => 'not punchin! Action Restricted'
            ], 200);
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $last_punching_details = AttendanceLog::where('user_id', $user_id)->orderBy('att_id', 'desc')->first();
        $work_loc = $last_punching_details->work_loc;
        $userdetails = User::where('id',$user_id)->first();
        $datas['employee'] = $userdetails->fullname;
        $datas['email'] = $userdetails->email;
        $dep_id = $userdetails->dep_id;
        if($dep_id == 2 || $dep_id == 46 || $dep_id == 51 || $dep_id == 52){
            $error_flag = true;
            if(!empty($at_data->work_report)) {
                $work_report = unserialize($at_data->work_report);
                $ticket_handled_no = 0;
				$tickets_pending_no = 0;
				$ticket_resolved_no = 0;
                foreach ($work_report as $key => $value) {
                    switch ($key) {
                        case 599:
                        case 703:
                        case 711:
                        case 691:
                            if($value['status'] != 1){
								$error_flag = false;
							}else{
								$ticket_handled_no = $value['reply'];
							}
                        break;
                        case 693:
                        case 600:
                        case 701:
                        case 709:
                            if($value['status'] != 1){
                                $error_flag = false;
                            }else{
                                $ticket_resolved_no = $value['reply'];
                            }
                        break;
                        case 694:
                        case 702:
                        case 710:
                        case 601:
                            if($value['status'] != 1){
								$error_flag = false;
							}else{
								$tickets_pending_no = $value['reply'];
							}
                        break;
                    }
                    // if(intval($tickets_pending_no) + intval($ticket_resolved_no) != intval($ticket_handled_no) ){
                    //     return response()->json([
                    //         'status' => false,
                    //         'message' => 'Enter exact number of resolved and pending tickets. Tickets Handled = Tickets Resolved + Tickets Pending'
                    //     ], 200);
                    // }
                }
            } else{
                $error_flag = false;
            }
        }
        if(($work_loc == 0) || ($work_loc == 1) || ($work_loc == 3)) {
            $ip_a[] = '103.61.12.146';
			$ip_a[] = '202.83.55.157';
			$ip_a[] = '202.88.227.250';
			$ip_a[] = '69.12.78.213';
            if(!in_array($ip, $ip_a)){
                return response()->json([
                    'status' => false,
                    'message' => 'IP Mismatch! Action Restricted'
                ], 200);				
			}
        }
        if((empty($at_data->punchout)) && (!empty($at_data->punchin))) {
            $att_id_4_wrkRpt = $last_punching_details->att_id;
            $last_punching_details->punchout = strtotime('now');
            $last_punching_details->punchout_ip = $_SERVER['REMOTE_ADDR'];
            $last_punching_details->att_status = 1;
            $tot_brk  = 0;
            $punchin = $at_data->punchin;
            $punchout = strtotime('now');
            if(!empty($at_data->total_break)) {
                $tot_brk = $at_data->total_break;
            }
            $time = $punchout - $punchin;
            $worked_time = $time - $tot_brk;
            $worked_times = $time - $tot_brk;
            $last_punching_details->worked_time = $worked_time;
            $last_punching_details->save();
            $datas['punchout'] = date('d M Y h:i a',$punchout);
            $worked_time = $worked_time/60;
            $worked_time_rem = $worked_time%60;
            $worked_time = $worked_time - $worked_time_rem;
            $worked_time = $worked_time/60;
            $datas['worked'] = round($worked_time)." hrs ".round($worked_time_rem)." min";
            $tot_brk = $tot_brk/60;
            $tot_brk_rem = $tot_brk%60;
            $tot_brk = $tot_brk - $tot_brk_rem;
            $tot_brk = $tot_brk/60;
            $datas['break'] = round($tot_brk)." hrs".round($tot_brk_rem)." min";
			
			$punchin_time = date('d-m-Y h:i a',$punchin);
            $datas['punchin'] = $punchin_time;
			$punchout_time = date('d M Y',$punchout);

            $total_break_tim = $tot_brk;
			$tot_wrkd_time = $worked_times;
			$lastsun = strtotime('last Sunday');
			$today = strtotime('now');
            $get_wrkd_hrs = WeeklyWorkingHour::with('user')->where('user_id',$user_id)->where('date','>=', $lastsun)
            ->orderBy('wrk_id','desc')->first();
            $get_calcs = SettingHour::get();
            $fixed_pending_hrs = $get_calcs[0]->pending_calc;
            $time_conv = explode(':',$fixed_pending_hrs);
            if($userdetails->desgn_id == 1) {
                $fix_pend_minutes  = 178200;
            } else {
                $fix_pend_minutes  = 148500;
            }
            if($get_wrkd_hrs) {
                $w_id = $get_wrkd_hrs->wrk_id;
                $sum = $get_wrkd_hrs->hrs_worked + ($tot_wrkd_time) ;
				$data_Wrk['extra_hrs'] = $get_wrkd_hrs->extra_hrs;
				$data_Wrk['flexi_hrs']    =$get_wrkd_hrs->flexi_hrs;
				$data_Wrk['hrs_worked'] 	=    $get_wrkd_hrs->hrs_worked;
				$data_Wrk['pending_hrs'] 	= $get_wrkd_hrs->pending_hrs;
                if($work_loc==3) {
                    $get_wrkd_hrs->extra_hrs =$last_punching_details->worked_time +$data_Wrk['extra_hrs'];
                } elseif($work_loc==4){
					// if flexi hrs punchin saving time 
                    $get_wrkd_hrs->flexi_hrs =$last_punching_details->worked_time +$get_wrkd_hrs->flexi_hrs;
				} else{
					$get_wrkd_hrs->hrs_worked = round($sum);    
					$get_wrkd_hrs->pending_hrs = round($get_wrkd_hrs->pending_hrs - $tot_wrkd_time);
				}
                $upd = $get_wrkd_hrs->save();
				$overtime=$get_wrkd_hrs->overtime;
            } else {
                $sum = $tot_wrkd_time;
                $wrkd_hrs = new WeeklyWorkingHour;
				$wrkd_hrs->user_id = $user_id;
				$wrkd_hrs->extra_hrs = 0;
				$wrkd_hrs->flexi_hrs =0;
				$wrkd_hrs->hrs_worked =0;
				$wrkd_hrs->pending_hrs = $fix_pend_minutes;
                $data_Wrk['hrs_worked'] = $wrkd_hrs->hrs_worked;
                $data_Wrk['pending_hrs'] = $wrkd_hrs->pending_hrs;
                $data_Wrk['flexi_hrs'] = $wrkd_hrs->flexi_hrs;
                $data_Wrk['extra_hrs'] = $wrkd_hrs->extra_hrs;

                if($work_loc==3){
					$wrkd_hrs->extra_hrs = $last_punching_details->worked_time +0;
					$wrkd_hrs->hrs_worked = 0;
				} elseif($work_loc==4){
				// if flexi hrs punchin saving time 
                    $wrkd_hrs->flexi_hrs = $last_punching_details->worked_time +0;
				} else {
					$wrkd_hrs->hrs_worked = round($sum) ;
					$wrkd_hrs->pending_hrs = round($fix_pend_minutes - $sum);
				}
                $wrkd_hrs->date = strtotime('now');
                $wrkd_hrs->save();
                $overtime ="00 : 00";
            }
            $datas['wrking_hrs'] = $this->GetRealTime($data_Wrk['hrs_worked']);
			$datas['pending_hrs']  = $this->GetRealTime($data_Wrk['pending_hrs']);
			$datas['flexi_hrs'] = $this->GetRealTime($data_Wrk['flexi_hrs']);
			$datas['extra_hrs'] = $this->GetRealTime($data_Wrk['extra_hrs']);
            $datas['overtime'] = $overtime;
            /*return response()->json([
                'data' => $datas,
                'message' => 'Success'
            ], 200);*/

            //send mail
            $team_id = $userdetails->team_id;
            $mail_ids = Team::select('mail_ids')->where('team_id',$team_id)->first();
            $mail__ids = $mail_ids->mail_ids;
            $wrk_lc_dt     = '';
			if($work_loc    == 0){
				$wrk_lc_dt .= "Regular";
			}elseif($work_loc == 1){
				$wrk_lc_dt .= "Swap Shift";
			}elseif($work_loc == 2){
				$wrk_lc_dt .= "Home Login";
			}elseif($work_loc == 3){
				$wrk_lc_dt .= "Extra Hours";
			}else{
				$wrk_lc_dt .= "Project Hours";
			}

            $punchin_ip      = $last_punching_details->punchin_ip;
			$punchout_ip     = $last_punching_details->punchout_ip;
            $datas['punchin_ip'] = $punchin_ip;
            $datas['punchout_ip'] = $punchout_ip;
            $datas['work_location'] = $wrk_lc_dt;

            $tot_brk = $last_punching_details->total_break;
            if($tot_brk) {
                $tot_brk = $tot_brk/60;
                $tot_brk_min = $tot_brk%60;
                $tot_brk = $tot_brk - $tot_brk_min ;
                $tot_brk = $tot_brk/60;
                $tot_break = round($tot_brk)." : ".round($tot_brk_min)." Hrs";
            } else {
                $tot_break = "00:00 Hrs";
            }
            $worktime = $last_punching_details->worked_time;
            if($worktime) {
                $worktime = $worktime/60;
                $worktime_min = $worktime%60;
                $worktime = $worktime - $worktime_min ;
                $worktime = $worktime/60;
                $tot_wrk = round($worktime)." : ".round($worktime_min)." Hrs";
            } else {
                $tot_wrk = "00:00 Hrs";
            }
            
            $activity_data = array();
            if($last_punching_details->work_report) {
                $daily_activity = unserialize($last_punching_details->work_report);
                foreach($daily_activity as $val) {
                    if(array_key_exists('activity',$val)){
                        array_push($activity_data,$val);

                    }              
                  }
            }
            $daily_activity_list = array();
            $daily_activity_list = $activity_data;
            if(intval($last_punching_details->sla_violation) > 0){
				$slaviolation_a['activity'] = 'SLA Violation';
				$slaviolation_a['status'] = 1;
				$slaviolation_a['field_type'] = 1;
				$slaviolation_a['reply'] = $last_punching_details->sla_violation;
				$slaviolation_a['time'] = '';
				$daily_activity_list[] = $slaviolation_a;
			}
            $month_id = date('m-Y');
            $a = strtotime("01-".$month_id);	
            $nor = date('Y-m-d',$a);
            $lastday = date('t',strtotime($nor));
            $time1 = date('d-m-Y',$a);
            $time2 = date($lastday."-".$month_id);
            $full_weekly_checklist = WeeklyActivity::select('weekly_activity.wa_id','weekly_activity.wa_activity','weekly_data.wd_status')
            ->join('weekly_data','weekly_data.weekly_id','=','weekly_activity.wa_id','left outer')
            ->where('weekly_activity.wa_field_type','0')->where('weekly_activity.dep_id',$dep_id)->whereNull('weekly_data.wd_status')
            ->get();
            $weekly_checklist = WeeklyActivity::select('weekly_activity.wa_id','weekly_activity.wa_activity','weekly_data.wd_status','weekly_data.wd_date')
            ->join('weekly_data','weekly_data.weekly_id','=','weekly_activity.wa_id')
            ->where('weekly_activity.wa_field_type','0')->where('weekly_activity.dep_id',$dep_id)
            ->where('weekly_data.wd_date','>=', strtotime($time1))->where('weekly_data.wd_date','<=', strtotime($time2))
            ->get();
            $full_weekly_workreport = WeeklyActivity::select('weekly_activity.wa_id','weekly_activity.wa_activity','weekly_data.wd_status')
            ->join('weekly_data','weekly_data.weekly_id','=','weekly_activity.wa_id','left outer')
            ->where('weekly_activity.wa_field_type','1')->where('weekly_activity.dep_id',$dep_id)->whereNull('weekly_data.wd_status')
            ->get();
            $weekly_workreport = WeeklyActivity::select('weekly_activity.wa_id','weekly_activity.wa_activity','weekly_data.wd_status','weekly_data.wd_date')
            ->join('weekly_data','weekly_data.weekly_id','=','weekly_activity.wa_id')
            ->where('weekly_activity.wa_field_type','1')->where('weekly_activity.dep_id',$dep_id)
            ->where('weekly_data.wd_date','>=', strtotime($time1))->where('weekly_data.wd_date','<=', strtotime($time2))
            ->get();
            $full_monthly_checklist = MonthlyActivity::select('monthly_activity.ma_id','monthly_activity.ma_activity','repeat_monthly_data.md_status')
            ->join('repeat_monthly_data','repeat_monthly_data.monthly_id','=','monthly_activity.ma_id','left outer')
            ->where('monthly_activity.ma_field_type','0')->where('monthly_activity.dep_id',$dep_id)->whereNull('repeat_monthly_data.md_status')
            ->get();
            $monthly_checklist = MonthlyActivity::select('monthly_activity.ma_id','monthly_activity.ma_activity','repeat_monthly_data.md_status')
            ->join('repeat_monthly_data','repeat_monthly_data.monthly_id','=','monthly_activity.ma_id','left outer')
            ->where('monthly_activity.ma_field_type','0')->where('monthly_activity.dep_id',$dep_id)
            ->where('repeat_monthly_data.md_date','>=', strtotime($time1))->where('repeat_monthly_data.md_date','<=', strtotime($time2))
            ->get();
            $full_monthly_workreport_act = MonthlyActivity::select('monthly_activity.ma_id','monthly_activity.ma_activity','repeat_monthly_data.md_status')
            ->join('repeat_monthly_data','repeat_monthly_data.monthly_id','=','monthly_activity.ma_id','left outer')
            ->where('monthly_activity.ma_field_type','1')->where('monthly_activity.dep_id',$dep_id)->whereNull('repeat_monthly_data.md_status')
            ->get();
            $monthly_workreport_act = MonthlyActivity::select('monthly_activity.ma_id','monthly_activity.ma_activity','repeat_monthly_data.md_status')
            ->join('repeat_monthly_data','repeat_monthly_data.monthly_id','=','monthly_activity.ma_id','left outer')
            ->where('monthly_activity.ma_field_type','1')->where('monthly_activity.dep_id',$dep_id)
            ->where('repeat_monthly_data.md_date','>=', strtotime($time1))->where('repeat_monthly_data.md_date','<=', strtotime($time2))
            ->get();
            if($dep_id==2 || $dep_id==46 || $dep_id==51 || $dep_id==52 ){
                $work_reports = WorkReport::where('user_id',$user_id)->where('att_id',$last_punching_details->att_id)->where('date', strtotime(date('d-m-Y')))
                ->orderBy('workreport_id','desc')->get();
                $ticket_details = TicketDetails::where('att_id', $last_punching_details->att_id)->where('user_id',$user_id)->get();
            } else {
                $ticket_details = array();
            }
                $mail__ids = explode(',',$mail__ids);
                try {
                    Mail::to($mail__ids)->send(new ActivityDetails($datas,$activity_data,$daily_activity_list,$weekly_checklist,$full_weekly_checklist,$full_weekly_workreport,$weekly_workreport,
            $full_monthly_checklist,$monthly_checklist,$full_monthly_workreport_act,$monthly_workreport_act,$ticket_details));
                } catch (\Exception $e) {
                    Log::info( "punchout work report mail:".$e->getMessage());
                }
            return response()->json([
                'data' => $datas,
                'message' => 'Success'
            ], 200);
        }
    }
    public function workscreenshort(Request $request) {
        $user_id = $request->user_id;
        $attendance = AttendanceLog::where('user_id',$user_id)->where('att_status',0)->orderBy('att_id','desc')->first();
        if(empty($attendance)) {
            return response()->json([
                'status' => false,
                'message' => 'Please Punchin!'
            ], 200);
        }
        $validated = $request->validate([
            'screenshots' => 'required',
        ]);
        if($request->hasFile('screenshots')){
            //foreach($request->file('screenshots') as $key=>$file) {
                $filenameWithExt = $request->file('screenshots')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('screenshots')->getClientOriginalExtension();
                $fileNameToStore = strtotime('now').'sc_'.$user_id.'.'.$extension;
                $path = $request->file('screenshots')->storeAs('public/screenshort',$fileNameToStore);
                $deskimage = new DeskImage;
                $deskimage->di_image_name = $fileNameToStore;
                $deskimage->di_date = strtotime('now');
                $deskimage->user_id = $user_id;
                $deskimage->att_id = $attendance->att_id;
                $result = $deskimage->save();
            //}
        }
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
    }

	private function get_breaks($break){
		$unser = unserialize($break);
		$count  = (is_bool($unser))?$unser:count($unser);
		$timing_a = [];
		foreach ($unser as $row) {
			if(array_key_exists('on', $row) && array_key_exists('off', $row)){
				$break_time = date('h:i:s A', $row['on'])." to ".date('h:i:s A', $row['off']);
				array_push($timing_a, $break_time);

				/*$total_diff = $row['on'] - $row['off'];
				$brk_rem 		      = $total_diff % 60; 
				$brk_hrs              = $total_diff1 - $brk_rem;
				$tot_brk_hrs          = $brk_hrs/60;
				$total_break_hours    = round($tot_brk_hrs)."Hrs ".round($brk_rem)." min";
				print_r('expression');*/
			}
		}

		return $timing_a;
	}
    private function get_wfh_cl_count($user_id, $callback="") {
        $total_wofhm_result = WFHManage::select('wfh_count')->where('user_id',$user_id)->where('is_active',1)->first();
        return $total_wofhm_result;
    }
}
