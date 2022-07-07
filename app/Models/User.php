<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Requst;
use App\Models\AttendanceLog;
use App\Models\WeeklyWorkingHour;
use App\Models\Performance;
use App\Models\WFHManage;
use App\Models\PromotionNotification;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function mail_exists($key) {
        $query =User::where('email',$key)->first();
        if( $query)  	{
            return true;	   	}
        else{
            return false;
        }
    } 
    public function team()
    {
        return $this->hasOne(Team::class,'team_id','team_id');
    }
    public function department()
    {
        return $this->hasOne(Department::class,'dep_id','dep_id');
    }
    public function designation()
    {
        return $this->hasOne(Designation::class,'desg_id', 'desgn_id');
    }
	public function performance()
    {
        return $this->hasOne(Performance::class,'user_id','id');
    }
	public function wfhmanage()
    {
        return $this->hasOne(WFHManage::class,'user_id','id');
    }
	public function promotion()
    {
        return $this->hasOne(PromotionNotification::class,'user_id', 'id');
    }
    public static function empid_exists($key) {
        $query =User::where('emp_id',$key)->first();
        if( $query)  	{
            return true;	   	}
        else{
            return false;
        }
    }
    public  static function get_users_with_teamids($team_a){

     $query=user::select('*')->whereIn('team_id', $team_a)->get();
   
	 return $query;

	}
    
    public static function get_users_with_depIds($deps_a){
	 $query=user::select('*')->whereIn('dep_id', $deps_a)->get();
   
	 return $query;
	}

    Public static function getEmployee_daily(){

        $query=user::select('*')->orderby('fullname','asc')->get();
        return $query;
	}

    public static function NoofWFH($user_id,$getLeaveResetDate){
		//$dates = $this->get_year_start_end();

		$query =AttendanceLog::select(DB::raw('count(att_id) as total'))->where('user_id', $user_id)
        ->where('work_loc', 2)
        ->where('punchin','>=', $getLeaveResetDate)->get();
       
		return $query;
	}
    Public static function NoofLOP($session_id,$getLeaveResetDate){
        $query=Requst::select(DB::raw('sum(lv_no) as total'))
        ->where('user_id',$session_id)
		->where('lv_type',4)	
		->where('lv_date','>=',$getLeaveResetDate)		
		->where('lv_status',1)->first();				
		
		return $query;
		
	}	
	Public static function NoofSWAP($session_id,$getLeaveResetDate){
        $query=Requst::select(DB::raw('sum(lv_no) as total'))
	     ->where('user_id',$session_id)
		 ->where('lv_type',5)
		 ->where('lv_date','>=',$getLeaveResetDate)	
		->where('lv_status',1)->first();			
		return $query;	
	}	
	Public static function NoofWFHfromRequest($session_id,$getLeaveResetDate){
        $query=Requst::select(DB::raw('sum(lv_no) as total'))
		->where('user_id',$session_id)
		->where('lv_type',3)	
		->where('lv_date','>=',$getLeaveResetDate)	
		->where('lv_date','<=',1547445316)		
		->where('lv_status',1)->first();				
		return $query;	
	}	
	public static function getExtraHours($user_id){
		$query=WeeklyWorkingHour::select('extra_hrs','pending_hrs')
		->where('user_id',$user_id)
		->orderby('wrk_id','desc')->first(); 
		return $query;	
	}
    
	Public static function GetRealTime($sec){

		$minte=round($sec/60);

		$min=($minte%60);

		$hrs=(($minte-$min)/60);

		$realtime=" ".($hrs)." hrs ".abs($min)." min";

		return $realtime;

	}
	
    
    public static  function getTicketsCount($user_id){

		$data		=User::workReportGraph($user_id);

		$result['resolved'] =0;

		$result['pending'] =0;

		$result['handled'] =0;

		$result['sla'] =0;
        Log::info($user_id);

		foreach ($data as $index => $report) {

			$tickets			 	   = (unserialize($report->work_report)); 
			if(isset($tickets[600])){
				$result['resolved']    = $result['resolved']+isset($tickets[600]['reply'])?(int)$tickets[600]['reply']:0;
			}
			if(isset($tickets[601])){
				$result['pending']     = $result['pending']+isset($tickets[601]['reply'])?(int)$tickets[601]['reply']:0;
			}
			if(isset($tickets[599])){
				$result['handled']     = $result['handled']+isset($tickets[599]['reply'])?(int)$tickets[599]['reply']:0;
			}

			$result['sla']     		  = $result['sla']+$report->sla_violation;

		}

		return($result); 

	}
    public static function workReportGraph($user_id){
		$query=AttendanceLog::select('work_report','punchin','sla_violation')
		->where('user_id',$user_id)
		->orderby('punchin','asc')->get();
		
		return $query;
	}

    public static function getDobConversion($dob){
	
		$year		  =	 (int)$dob;
		$fromDate=Carbon::parse($year)->format('d-m-Y');
		$fromDate =date('Y-m-d', strtotime($fromDate. ' + 1 days'));
		return $fromDate;

	}
    public static  function  getLeaveResetDate($date_of_join){
		$year_previous			  =	  date('Y')-1;
		if($date_of_join>1515954600){// 1515954600-- Monday, January 15, 2018 12:00:00 AM 
			//$from_date				=	
			$joining_month		=	date('m',$date_of_join); 
			$joining_day		  =   date('d',$date_of_join); 
			$new_joining_date =   strtotime("$joining_day-$joining_month-$year_previous");
		}else{
			$new_joining_date =   strtotime("15-01-$year_previous");
		}
		$monthAndDay = date('md',$new_joining_date);
		$today = date('md');
		$_joining_month		=	date('m',$new_joining_date); 
		$_joining_day		  =   date('d',$new_joining_date); 
		if($today<$monthAndDay){ // compare date and adding year 
			$fromYear = date('Y')-1;
		}else{
			$fromYear = date('Y');
		}
		$fromDate = strtotime("$_joining_day-$_joining_month-$fromYear");
		return $fromDate;
	}

	//Close new punchin code
	//Insert daily act work report for first time
	Public static function Insert_wrk_rpt($data,$user_id,$att_id){
       DB::table('attendance_log')->where('user_id',$user_id)
	           ->where('att_id',$att_id)->update($data);
	}
}
