<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\PerformanceHistory;
use App\Models\Performance;
use App\Models\WeeklyWorkingHour;
use Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use stdClass;
use App\Models\AttendanceLog;
class PerformanceController extends Controller
{
    Public  function performance($id){

		$result=array();


		$result	 = PerformanceHistory::getEmployee($id);	//dd($result	);
	//dd($result[0]['performance_id']	);
		unset($result[0]['password'])	;
		$result[0]['PE'] = PerformanceHistory::pe_history($result[0]['performance_id']);	
//dd($result[0]);
		$result[0]['weekly_status'] =PerformanceHistory::getWeeklyStatus($id);
	//	dd(	$result[0]['weekly_status'] );
	if(isset($result[0]['weekly_status'])){
		if(isset($result[0]['weekly_status']->overtime)){

			$result[0]['weekly_status']->overtime=User::GetRealTime($result[0]['weekly_status']->overtime);
			$result[0]['weekly_status']->extra_hrs=User::GetRealTime($result[0]['weekly_status']->extra_hrs);
			$result[0]['weekly_status']->pending_hrs=User::GetRealTime($result[0]['weekly_status']->pending_hrs);
			$result[0]['weekly_status']->wrk_id=$result[0]['weekly_status']->wrk_id;


		}elseif($result[0]['weekly_status']->overtime==null){//dd($result[0]['weekly_status']);
			$wo_rtime=0;
		//dd('');
			$unixtime=strtotime('monday this week');
			$unixtimeout=strtotime('tuesday this week');

	//dd($unixtime);
			$date = date("d-m-Y H:i:s",$unixtimeout);

			//dd($date);
	// 		$log=AttendanceLog::where('user_id',$id)->whereBetween('punchout', [$unixtime, $unixtimeout])->get();

	// 			$wo_time=0;
			
	// 			if(count($log)>0){
	// 			foreach($log as $wtime){
	// 			$wo_time=$wo_time+$wtime->worked_time;
		
	// 			} 
	// 		//	$wo_rtime=WeeklyWorkingHour::GetRealTimeSecond($wo_time);	dd( $wo_rtime);
	// 		//dd( $wo_time);
	// 			$wo_rtime =$wo_time;
				 
	// 		    // $user_pr=$data_time_p- $wo_rtime;
	// 			// $user['mandatory_hrs']=WeeklyWorkingHour::GetRealTimeSecond( $user_pr);
	// //dd($user['wrking_hrs']);
	// 		  }
			
			$result[0]['weekly_status']->overtime=User::GetRealTime($result[0]['weekly_status']->overtime);
			$result[0]['weekly_status']->extra_hrs=User::GetRealTime($result[0]['weekly_status']->extra_hrs);
			$result[0]['weekly_status']->pending_hrs=User::GetRealTime($result[0]['weekly_status']->pending_hrs - $wo_rtime);
			$result[0]['weekly_status']->wrk_id=$result[0]['weekly_status']->wrk_id;

		}
	}
		else{
		
			$result[0]['weekly_status']=new stdClass();
			$result[0]['weekly_status']->overtime=User::GetRealTime(0);
			$result[0]['weekly_status']->extra_hrs=User::GetRealTime(0);
			
			if( $result[0]['desgn_id']==1){			
				$result[0]['weekly_status']->pending_hrs			 = WeeklyWorkingHour::GetRealTimeSecond(178200);
			}else{
				$result[0]['weekly_status']->pending_hrs		 =WeeklyWorkingHour::GetRealTimeSecond(148500);
			}
		//	$result[0]['weekly_status']->pending_hrs=User::GetRealTime($result[0]['weekly_status']->pending_hrs);
			$result[0]['weekly_status']->wrk_id=1;

		}

		return response()->json([
            'data' => $result[0],
            'message' => 'Success'
        ], 200);

	} 

	Public function updatepoint(Request $request,$idd){

		$field= $request->field;
		$id=$request->user_id;
		//print_r($this->lang[$field]);

		$history = array(

		'point' =>$request->value,

		'criteria' =>$this->lang($field),

		'time'=>strtotime('now'),

		'cri_type'=>$this->category($field),

		'performance_id'=>$id,

		'status'=>1,

		'comments'=>$request->comment

		);

		

		

		$performance=array(

		$field =>$request->new_value

		); 

				

			$user_id=$id;		

			$day=strtotime('first day of this month 00:00:00');	 	
			$isMonthExist=Performance::check_month($day,$user_id);
//dd($isMonthExist);
			if($isMonthExist==TRUE){

		
			$set=Performance::where('user_id', $id)
		               ->update($performance);

			}else{ 


			$data=Performance::where('user_id',$user_id)
                           ->orderby('performance_id','desc') 
                           ->limit(1)->get();
			//	dd(	$data[0]['performance_id']);					dd(	$data);
		//	dd(	$data);

			unset($data[0]['performance_id']);

			$data[0]['user_id']=$user_id;

			$data[0]['date']=strtotime('now');

			$data[0][$field]=$request->new_value;

            $insert_a =  json_decode( json_encode($data[0]), true);
//dd( $insert_a);
            $result=DB::table('performance')->insertGetId($insert_a);
//d/d($result);
			$history['performance_id']=$result;// insert Id

			}

		    $set=DB::table('performance_history')->insert($history);			
 		

            if($set) {
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

	Public function category($key){

		$pe=array(

			'trialpperf' => 'Trial Period Performance',

			'servicecancellation' => 'Service Cancellation',

			'preview' => 'Public review',

			'creview' =>'Client review',

			'tquality' => 'Work Quality',

			'cquality' => 'Communication',			

			'pviolation' => 'Policy Violation',

			'cypviolation' => 'Company Policy Violation',

			'slaviolation' => 'SLA Violation',

			'wreport' => 'Work Reports',

			'ChallengeOfTheDay'  => 'Challenge Of The Day',			

			'warning' => 'Warning',

			'suspension' => 'Suspension ',			

			'awards' => 'Awards & Achievements',
			'clientvio' => 'Client Policy Violation',

			

		);

		

		$ie=array(

			'goldenresponse' => 'Golden Responses',

			'treplies' =>'Thanks replies',

			'blogpost' => 'Blog Posts',

			'interviews' => 'Interviews',

			'training' => 'Training',	

			'certifications' => 'Certifications',	

			'seminars' => 'Seminars'

		);



		$ce=array(

			'codeof' =>  'Code of conduct',

			'ssmedia' =>  'Social Media Engagements',

			'extracurricular'  => 'Extracurricular Activities',

			

			

		);



		if(array_key_exists($key,$pe)){

			return 1;

		}

		if(array_key_exists($key,$ie)){

			return 2;

		}

		if(array_key_exists($key,$ce)){

			return 3;

		}

		

	}
	Public function lang($key){
//dd($key);
	$lang = array(

		'preview' => 'Public review',

		'creview' =>'Client review',

		'tquality' => 'Work Quality',

		'cquality' => 'Communication',

		'treplies' =>'Thanks replies',

		'pviolation' => 'Policy Violation',

		'cypviolation' => 'Company Policy Violation',			

		'slaviolation' => 'SLA Violation',

		'wreport' => 'Work Reports',

		'warning' => 'Warning',

		'clientvio' => 'Client Policy Violation',

		'suspension' => 'Suspension ',

		'blogpost' => 'Blog Posts',

		'seminars' => 'Seminars',

		'training' => 'Training',

		'codeof' =>  'Code of conduct',

		'linkedin' => 'Linkedin Engagements',

		'fb' =>  'Facebook Engagements',			

		'twitter' =>  'Twitter Engagements',

		'insta' =>  'Instagram Engagements',			 

		'ssmedia' =>  'Social Media Engagements',

		'awards' => 'Awards & Achievements',

		'goldenresponse' => 'Golden Responses',

		'ChallengeOfTheDay'  => 'Challenge Of The Day',

		'extracurricular'  => 'Extracurricular Activitiess',

		'interviews' => 'Interviews',

		'certifications' => 'certifications',

		'trialpperf' => 'Trial Period Performance',

		'servicecancellation' => 'Service Cancellation'
	

	);
	return $lang[$key];

 } 
    public function overtime_reset(Request $request){
       $wrk_id = $request->work_id;
      	$data = array(
        "overtime" => 0,
        "extra_hrs" => 0
           ); 
        $result =WeeklyWorkingHour::where('wrk_id', $wrk_id)->first();
		//dd(  $result);
		$result->overtime =0;
		$result->extra_hrs=0;
		$result->save();
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

   function updateMandatory(Request $request){
    $work_id  = $request->work_id;
    $seconds       = $request->seconds;
	$user_id       = $request->user_id;

    if($work_id && isset($seconds)){
        $pendingHours	=	PerformanceHistory::getPendingHours($work_id,$user_id);
		//dd(  $pendingHours);
         $reduceHours     =  $pendingHours - $seconds;
        if(	PerformanceHistory::updateMandatory($work_id,$reduceHours,$user_id)){
            $response['status']     =  1;
            $response['new_time']        = User::GetRealTime($reduceHours);
            $response['message'] = "Updated Successfuly";
         }
   		 return response()->json([
   			'data' => $response,
   			'message' => 'Success'
   		], 200);
      }else {
   	   return response()->json([
   		   'status' => false,
   		   'message' => 'Error'
   	   ], 200);
     }   
}


  public function manage_warning(Request $request){
  
  	$user_id=$request->user_id;
  
  	$update_a['warning_level'] = $request->warning_level;
  
  	$update_a['warning_last_update'] = date('Y-m-d');
  
  	
  
  	$result = User::where('id', $user_id)
	           ->update($update_a);
  
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

	Public  function evaluationHistory($id){

		$result=array();


		$result	 = PerformanceHistory::getEmployee($id);	
	
		$result = PerformanceHistory::pe_evaluation_history($result[0]);	
        $pe_history=array();
		foreach($result as $pe){
          $per_data['id'] = $pe['ph_id'];
          $per_data['criteria'] = $pe['criteria'];
          $per_data['date'] = date("d M Y ",$pe['time']);
          $per_data['comments'] = $pe['comments'];
          $per_data['point'] = $pe['point'];
          array_push( $pe_history,$per_data);
		}


		return response()->json([
            'data' => $pe_history,
            'message' => 'Success'
        ], 200);

	} 

}
