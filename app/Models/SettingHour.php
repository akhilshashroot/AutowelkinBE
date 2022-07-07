<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingHour extends Model
{
    use HasFactory;
    protected $table = 'settings_hrs';
    public $timestamps = false;
    protected $primaryKey = 'settings_id';

    //Getting hrs wrkd form db
	//Getting fixed numerics from settings table
	Public static function get_calcs(){
        $q =SettingHour::select('*')->get();
		return $q;
	}

    Public static function GetRealTime($sec){
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

    public static function get_breaks($break){
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


}
