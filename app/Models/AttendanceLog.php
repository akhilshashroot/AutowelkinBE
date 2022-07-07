<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkLocation;
use App\Models\WFHBreak;
use App\Models\User;

class AttendanceLog extends Model
{
    use HasFactory;
    protected $table = 'attendance_log';
    protected $primaryKey = 'att_id';
    public $timestamps = false;
    public   function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public  function location()
    {
        return $this->hasOne(WorkLocation::class,'id','work_loc');
    }
    public  function wfhbreak()
    {
        return $this->belongsTo(WFHBreak::class,'p_id','att_id');
    }

    Public static function getallattLog($user_id,$crntMonth){
		$query=AttendanceLog::select('attendance_log.*', 'wl.loc_title')
	            ->join('work_loc as wl', 'wl.id', '=','attendance_log.work_loc')
		        ->where('attendance_log.user_id',$user_id)
	            ->where('attendance_log.punchin_date', 'like', '%' . $crntMonth. '%')
		        ->orderby('attendance_log.att_id', "desc")->get(); 
             //   dd($query);
		return $query;
}
    public static function workReportGraph($user_id){
        $query =  AttendanceLog::select('work_report','punchin','sla_violation')
                      ->where('user_id',$user_id)
                      ->orderby('punchin','asc')->get();
        return $query;
    }

    Public static function Get_All_att_log($user_id){
        $query = AttendanceLog::where('user_id',$user_id)
	             ->where('att_status',0)->limit(1)
		         ->orderby('att_id', "desc")->get();
		return $query;
	}
    
    Public  static function get_dailyStatus($user_id){ 
        $query =AttendanceLog::select('work_report', 'att_id','sla_violation')
		->where('user_id',$user_id)
		->where('att_status',0)->get()->toArray();
       // dd(  $query);
		return $query;
	}

	public static function get_dailyStatus_with_date($user_id, $start_date, $end_date){
	   $query =AttendanceLog::select('work_report', 'att_id','sla_violation')
	                       ->where('user_id',$user_id)
	                       ->where('punchin', '>=',$start_date)
	                       ->where('punchin', '<=',$end_date)->get();
		return $query->toArray();
			
	}
}
