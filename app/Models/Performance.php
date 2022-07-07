<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PerformanceHistory;
use DB;
class Performance extends Model
{
    use HasFactory;
    protected $table = 'performance';
    public $timestamps = false;
    protected $primaryKey = 'performance_id ';
    protected $fillable = [
        'user_id', 'date', 'preview','creview',
        'tquality','cquality','treplies','pviolation',
        'slaviolation','wreport',
        'skypeactivity', 'warning', 'suspension','blogpost',
        'seminars','codeof','training','codeof',
        'linkedin','fb','twitter', 'insta', 'comments','ssmedia',
        'awards','goldenresponse','cypviolation','ChallengeOfTheDay',
        'extracurricular','interviews','certifications','trialpperf','servicecancellation'
    ];

    Public static function check_month($day,$id){
		
        $query =  Performance::where('user_id',$id)
		        ->where('date','>=',$day)->get();
	   	if($query->count() > 0) {
	   		return TRUE;
        }else{
	   		return FALSE;
	   	}
	}
    Public static function sumofc1_latest($id){
		$d1 = strtotime(date("Y-m-01 0:0:0"));
		$d2 = strtotime(date("Y-m-31 12:59:59"));
		$query =PerformanceHistory::select(DB::raw('sum(point) as total'))
		          ->where('time','>=', $d1)
		          ->where('time','<=', $d2)
		          ->where('cri_type',1)
		          ->where('performance_id',$id)->get();  
//		return($id);
		return $query;
//		return $this->db->last_query();
	}
	Public static function sumofc2_latest($id){
		$d1 = strtotime(date("Y-m-01 0:0:0"));
		$d2 = strtotime(date("Y-m-31 12:59:59"));
		$query =PerformanceHistory::select(DB::raw('sum(point) as total'))
 	 	        ->where('time', '>=', $d1)
	 	        ->where('time', '<=', $d2)
	            ->where('cri_type',2)
	            ->where('performance_id',$id)->get(); 
		return $query;
//		return $this->db->last_query();
	}
	
	Public static function sumofc3_latest($id){
		$d1 = strtotime(date("Y-m-01 0:0:0"));
		$d2 = strtotime(date("Y-m-31 12:59:59"));
        $query =PerformanceHistory::select(DB::raw('sum(point) as total'))	
 		->where('time', '>=', $d1)
		->where('time' ,'<=', $d2)
		->where('cri_type',3)
		->where('performance_id',$id)->get(); 
		return $query;
//		return $this->db->last_query();
	}
	
	Public static function total_pe($id){

		$query =Performance::select(DB::raw('SUM(preview + creview + tquality + cquality + pviolation + cypviolation + slaviolation + wreport + warning + suspension + awards + ChallengeOfTheDay + trialpperf + servicecancellation)  as total_pe'))
        ->where('performance_id', $id)->get(); 

		return $query;
	}

	Public static function total_ie($id){

        $query =Performance::select(DB::raw('SUM(goldenresponse + treplies + blogpost + training + interviews + certifications + seminars)  as total_ie'))
	             ->where('performance_id', $id)->get(); 
		return $query;
	}
	
	Public static function total_ce($id){
		$query =Performance::select(DB::raw('SUM(codeof + ssmedia + extracurricular)  as total_ce'))
        ->where('performance_id', $id)->get(); 
		return $query;
	}

}
