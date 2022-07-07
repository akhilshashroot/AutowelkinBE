<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyWorkingHour extends Model
{
    use HasFactory;
    protected $table = 'weekly_work_hrs';
    protected $primaryKey = 'wrk_id';
    public $timestamps = false;


    //Insert working hours
	//For displaying dashboard
	Public static function get_pending_working($lastsun,$today_time,$user_id){
        $query = WeeklyWorkingHour::select('*')
		                  ->where('user_id', $user_id)
	                      ->where('date', '>=', $lastsun)
	                      ->orderby('wrk_id','desc')
	                      ->limit(1)->get();
		return $query;
//		return $this->db->last_query();
	}
    Public static function GetRealTimeSecond($sec){
		$minte=round($sec/60);
		$min=($minte%60); 
		$hrs=(($minte-$min)/60);
		$min = abs($min);
		if($hrs == 0){
			$hrs='00';
		}
		if($min<10){
			$min = '0'.$min;
		}
		$realtime= $hrs.":".$min;
		return $realtime;
	}

	public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
