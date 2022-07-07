<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requst;
use App\Models\Performance;
use App\Models\Assignment;
use App\Models\WeeklyWorkingHour;
use App\Models\SettingHour;
use App\Models\AttendanceLog;
use App\Models\linkedinNotify;
use Hash;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $users = User::with('team','department','designation')->join('performance', 'performance.user_id','=','users.id')
        ->orderby("performance.performance_id", "desc")
        ->where('id',$id)->first();
        
        $user['id'] = $users->id;
        $user['emp_id'] = $users->emp_id;
        $user['performance_id'] = $users->performance_id;
        $user['email'] = $users->email;
        $user['phone'] = $users->phone;
        $user['fullname'] = $users->fullname;
        $user['dob'] =User::getDobConversion($users->dob);      
        $user['team_id'] = $users->team_id;
        $user['desgn_id'] = $users->desgn_id;
        $user['dep_id'] = $users->dep_id;
		$user['warning_level'] = $users->warning_level;

		$ext=($users->img_file)?$users->img_file:$users->emp_id.'.jpg';
		$path = public_path('storage/picture/'.$ext);

		if(file_exists($path)){
			$user['profile_pic'] =env('APP_URL').'storage/picture/'.$ext;
		} else {
			$user['profile_pic'] =env('APP_URL').'storage/picture/avatar.png';
		}
        if(isset($users->designation)) {
            $user['designation'] = $users->designation->designation;
        } else {
            $user['designation'] = "";
        }
        if(isset($users->team)) {
            $user['team'] = $users->team->name;
        } else {
            $user['team'] = "";
        }
        if(isset($users->department)) {
            $user['department'] = $users->department->dep_name;
        } else {
            $user['department'] = "";
        }
        $user['bloodgroup'] =$users->bloodgroup;
        $user['cert_list'] =$users->cert_list;

        $getLeaveResetDate		=User::getLeaveResetDate($users['date_of_join']);
			$crntMonth=date('m-Y');
			$crntMonthLastday=date("d", strtotime('last day of this month'));
			$tday=date("j", strtotime('now'));
	/**
	 * Create monthly array
	 */

		for($i=$tday;$i>0;$i--){
			$daily_logs[$i]=array();
		}

		$att_log=AttendanceLog::getallattLog($id,$crntMonth);
       
		$shiftcount=0;
		foreach($att_log as $att_row){

			$cday=date('j',$att_row['punchin']);
			@$daily_log['loc_title'] = $att_row['loc_title'];
			@$daily_log['work_loc'] = $att_row['work_loc'];
			@$daily_log['punchin_time']=date('d MY  h:i a',$att_row['punchin']);
		//	@$daily_log['punchout_time']=date('d MY  h:i a',$att_row['punchout']) ?: "Haven't Punched Out";
			@$daily_log['punchin_ip']=$att_row['punchin_ip'] ?: "--";
			@$daily_log['punchout_ip']=$att_row['punchout_ip'] ?: "-- ";
			if($att_row['punchout'] && $att_row['punchout_ip']==""){
				$daily_log['punchout_ip']		= "Force Punchout";
			}
			@$daily_log['worked_time']=SettingHour::GetRealTime($att_row['worked_time']);
			@$daily_log['total_break']=SettingHour::GetRealTime($att_row['total_break']);
			@$daily_log['break_times']= SettingHour::get_breaks($att_row['break']);
			@$daily_log['idle_time']=SettingHour::GetRealTime($att_row['total_time']);
			$daily_logs[$cday][]=$daily_log;
			if($att_row['worked_time'] >10800){
				$shiftcount=$shiftcount+1;
			}
		}
		//$result->daily_log=$daily_logs;

			/** 
			 * current month sum
			 */
			$sumc1 = Performance::sumofc1_latest($users->performance_id);
			$sumc2 = Performance::sumofc2_latest($users->performance_id);
			$sumc3 = Performance::sumofc3_latest($users->performance_id);

			$sumtotal_pe  = Performance::total_pe($users->performance_id); 
			$sumtotal_ce  = Performance::total_ce($users->performance_id); 
			$sumtotal_ie  = Performance::total_ie($users->performance_id); 
			/**
			 * close current month sum
			 */	
			//$result = $re;
			$user['sum1'] = $sumc1;
		    $user['sum2'] = $sumc2;
		    $user['sum3'] = $sumc3;

			$user['performance_score'] = $sumtotal_pe[0]['total_pe'];
			$user['cultural_score'] = $sumtotal_ce[0]['total_ce'];

		//	$user['sum5'] = $sumc3;
			$user['integrity_score'] = $sumtotal_ie[0]['total_ie'];
            /**
  * .....Start working hours and pending hours display....
  */
	
		$lastsun 			= strtotime('last Sunday');
		$today_time 		= strtotime('now');
		$hours_rows 		= WeeklyWorkingHour::get_pending_working($lastsun,$today_time,$id);	
		$data_time_c=0;
		$data_time_p=0;

		if(count($hours_rows) > 0){
			$wh_minute = ($hours_rows[0]['hrs_worked']);
			$wh_hr     = $wh_minute; 
			$wh_minute=round($wh_minute);
			$totalMinutes=abs($wh_minute%60);
			$totalHrs=($wh_minute-$totalMinutes)/60;
			$user['wrking_hrs']=WeeklyWorkingHour::GetRealTimeSecond($hours_rows[0]['hrs_worked']);
			$data_time_c=$hours_rows[0]['hrs_worked'];
			$PendingHrs=round($hours_rows[0]['pending_hrs']);
			$ph_minute = $PendingHrs%60;
			$ph_hr     = ($PendingHrs - $ph_minute)/60;
			$data_time_p=$hours_rows[0]['pending_hrs'];
			$user['mandatory_hrs'] =WeeklyWorkingHour::GetRealTimeSecond($hours_rows[0]['pending_hrs']);
			$user['extra_hrs'] =WeeklyWorkingHour::GetRealTimeSecond($hours_rows[0]['extra_hrs']);
			$user['overtime'] =WeeklyWorkingHour::GetRealTimeSecond($hours_rows[0]['overtime']);
			$user['flexi_hrs'] =WeeklyWorkingHour::GetRealTimeSecond($hours_rows[0]['flexi_hrs']);
		}else{
			$res                				= SettingHour::get_calcs();
			$user['wrking_hrs'] 				 = "00:00";
			$user['overtime'] 					 = "00:00";
			$user['extra_hrs']  				 = "00:00";
			$user['flexi_hrs']  				 = "00:00";

			if( $users->desgn_id==1){			
				$user['mandatory_hrs']				 = WeeklyWorkingHour::GetRealTimeSecond(178200);
				$data_time_p=178200;

			}else{
				$user['mandatory_hrs']				 =WeeklyWorkingHour::GetRealTimeSecond(148500);
				$data_time_p=148500;
			}
		}
      
            $user['countshift'] 					= $shiftcount;
            // $user['casual']							=User::Noofcasualleaves($session_id,$getLeaveResetDate);
			// $user['sick	']								=User::Noofsickleaves($session_id,$getLeaveResetDate);
			// // $user->wfh								=User::NoofWFH($session_id,$getLeaveResetDate);
			// //remove future 
			// $result->wfh->total						=$result->wfh->total+User::NoofWFHfromRequest($session_id,$getLeaveResetDate)->total;
			$user['holiday'] = (Requst::NoHolidaysLeave( $users->id, $getLeaveResetDate)[0]['total'])?Requst::NoHolidaysLeave( $users->id, $getLeaveResetDate)[0]['total']."/10":"0/10";
            $user['casual']	 =(Requst::Noofcasualleaves($users->id,$getLeaveResetDate)[0]['total'])?Requst::Noofcasualleaves($users->id,$getLeaveResetDate)[0]['total']."/6":"0/6";
            $user['medical'] =	(Requst::Noofsickleaves($users->id,$getLeaveResetDate)[0]['total'])?Requst::Noofsickleaves($users->id,$getLeaveResetDate)[0]['total']."/6":"0/6";
            $user['wfh']	=Requst::NoofWFH($users->id,$getLeaveResetDate)[0]['total']+Requst::NoofWFHfromRequest($users->id,$getLeaveResetDate)[0]['total'];

            //remove future 
			 $user['lop']			=(Requst::NoofLOP($users->id,$getLeaveResetDate)[0]['total'])?Requst::NoofLOP($users->id,$getLeaveResetDate)[0]['total']:0;	
			 $user['swap_count'	]	=(Requst::NooSwap($users->id,$getLeaveResetDate)[0]['total'])?Requst::NooSwap($users->id,$getLeaveResetDate)[0]['total']:0;	
             $user['completed_task'] = Assignment::getcompleted($users->id)[0]['count'];
             $user['pending_task'] = Assignment::getpending($users->id)[0]['count'];
             $user['assigned_task']=$user['completed_task']+  $user['pending_task'] ;
            // $user['shiftcount']=$shiftcount;
            //notification
            $checkstatus= linkedinNotify::where('not_user',$users->id)
                         ->where('not_status', 1)->first();
             if(isset($checkstatus)){
                $user['notif_flag']=1;
            }else{  
                $user['notif_flag']=0;
            }

			
	
// 			$unixtime=strtotime('monday this week');
// 			$unixtimeout=strtotime('tuesday this week');

// 	//dd($unixtime);
// 			$date = date("d-m-Y H:i:s",$unixtimeout);

// 			//dd($date);
// 			$log=AttendanceLog::where('user_id',$id)->whereBetween('punchout', [$unixtime, $unixtimeout])->get();
// 				$wo_time=0;
// 			   //  if(($id !=="351") && ($id !=="406") && ($id !=="353")){

// 			if($id=="742" ){
// // 					//dd($id);
// // 				foreach($log as $wtime){
// // 				$wo_time=$wo_time+$wtime->worked_time;
// 		dd($hours_rows);
// 				} 
// 			//	$wo_rtime=WeeklyWorkingHour::GetRealTimeSecond($wo_time);	dd( $wo_rtime);
// 			//dd( $wo_time);
// 				$wo_rtime =$wo_time;
// 				$user['wrking_hrs']= 	$data_time_c;
			
// 				// $wo_rtime =str_replace(':', '.', $user['wrking_hrs']);
// 				// //dd(is_numeric($user['wrking_hrs']));
//              	// $user['wrking_hrs']= str_replace(':', '.', $user['wrking_hrs']);
// 				 $user_wr= $user['wrking_hrs']+$wo_rtime ;
				
// 				 $user['wrking_hrs']= WeeklyWorkingHour::GetRealTimeSecond( $user_wr);
// 				 $user_pr=$data_time_p- $wo_rtime;
// 				 $user['mandatory_hrs']=WeeklyWorkingHour::GetRealTimeSecond( $user_pr);
// 	//dd($user['wrking_hrs']);
// 			//  }
// // 			  if($id=="742"){
// // //dd($data_time_p);
// }
		//	}
            //remove future 
		//	$result->wfh->total						=$result->wfh->total+$this->User_model->NoofWFHfromRequest($session_id,$getLeaveResetDate)->total;
			// $user['lop']								=User::NoofLOP($session_id,$getLeaveResetDate);	
			// $user['swap_count']								=User::NooSwap($session_id,$getLeaveResetDate);	
        return response()->json([
            'data' => $user,
            'message' => 'Success'
        ], 200);
    }

public 	function timeToSeconds($time)
{
    $arr = explode(':', $time);
//	$min= $arr[0] ;dd($arr);
    if (count($arr) === 3) {
        return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
    }
	//dd($arr[1]);
	//  $min= $arr[1] ;
	$min= isset($arr[1])? $arr[1] * 60:0;
    return $arr[0] * 3600+$min ;
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

        $validated = $request->validate([
            'fullname' => 'required',
        ]);
        $user = User::find($id);
		if(!$user ){
			return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
		}
                 
		if($request->fullname){

			$user->fullname = $request->fullname;

		}				

	

		if($request->password){

			$user->password = Hash::make($request->password);

		}																

		if( $request->phone){

			$user->phone = $request->phone;		 

		}																

																

		if( $request->dob){

			$user->dob = strtotime( $request->dob);

		}																				


		if($request->cert_list){

			$user->cert_list = $request->cert_list;

		}
        if( $request->bloodgroup){

			$user->bloodgroup =$request->bloodgroup;

		}		

	
        $result = $user->save();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
      
    }
    public function getuserWorkandBreaktime(Request $request,$user_id) {
	
        $datas = AttendanceLog::with('user','location','wfhbreak')->where('user_id',$user_id)->orderBy('punchin', 'asc')->get();
        if(count($datas)<=0){
			return response()->json([
				'data' =>'',
				'message' => 'Success'
			], 200);
		}
		$fromD=date('Y-m-d',$datas[0]->punchin);
		$ToD=date('Y-m-d');
		$startDate = Carbon::createFromFormat('Y-m-d', $fromD);
        $endDate = Carbon::createFromFormat('Y-m-d', $ToD);
  
        $period = CarbonPeriod::create($startDate, $endDate);
   
        //dd($period->toArray());
		/*$period = new DatePeriod(
			new DateTime($fromD),
			new DateInterval('P1D'),
			new DateTime('tomorrow')
	   );*/
	   foreach ($period as $key => $value) {
		   $flag					 = 0;
		   $WT		   				= 0;
		   $BT						= 0;
		   $result[$key]['flexi']  	= 0;
		   $result[$key]['extra']     = 0;
		   $result[$key]['work']     = 0;
		foreach ($datas as $index=>$data){
			if(date("Y-m-d",$data->punchin)==$value->format('Y-m-d')){
				$flag								=1;
				$result[$key]['date'] 		= date("Y-m-d",$data->punchin);
				 $WT 							 = $WT+(int)$data->worked_time;
				 $BT                               = $BT+(int)$data->total_break;
				$workingTime			    = $this->convertToHour($data->worked_time);
				$BreakTime			 	     = $this->convertToHour($data->total_break);
				if($data->work_loc==4){
					$result[$key]['date']      = date("Y-m-d",$data->punchin);
					$result[$key]['break']    = round($BreakTime,2);
					$result[$key]['flexi']  	= round($workingTime,2);
					$result[$key]['status']     = "flexi";
				}elseif($data->work_loc==3){
					$result[$key]['date']      = date("Y-m-d",$data->punchin);
					$result[$key]['break']    = round($BreakTime,2);
					$result[$key]['extra']     = round($workingTime,2);
					$result[$key]['status']     = "extra";
				}else{
					$result[$key]['date']      = date("Y-m-d",$data->punchin);
					$result[$key]['work']     = round($workingTime,2);
					$result[$key]['break']    = round($BreakTime,2);
					$result[$key]['status']     = "regular";
				}

			}
		
		}
		if($flag==0){
			$result[$key]['date'] = $value->format('Y-m-d');
				$result[$key]['work'] 	= 0;
				$result[$key]['break'] = 0;
				$result[$key]['flexi']  	= 0;
				$result[$key]['extra']     = 0;
				$result[$key]['status']     = "none";
		}
	}

	return response()->json([
        'data' => $result,
        'message' => 'Success'
    ], 200);
    }
	public function convertToHour($sec){
		$seconds		  = ((int)$sec-((int)$sec%60));
		$minutes		  = (int)$seconds/60;
		$minMod			 = ((int)$minutes%60);
		$min		 		= (int)$minMod/100;
		$hrs			  	 = (($minutes-$minMod)/60);
		$realtime  		   = $hrs+$min;
		return $realtime;
	}
}
