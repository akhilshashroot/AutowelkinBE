<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WeeklyWorkingHour;
use App\Models\Comment;
use App\Models\Performance;
use DB;
class PerformanceHistory extends Model
{
    use HasFactory;
    protected $table = 'performance_history';
    public $timestamps = false;
    protected $primaryKey = 'ph_id ';
    protected $fillable = [
        'time', 'criteria', 'cri_type','point',
        'status','comments','performance_id'
    ];



    public static function pe_history($id){
        $query = 	PerformanceHistory::select('performance_history.*','performance.user_id')
		            ->join('performance','performance_history.performance_id','=','performance.performance_id')
		            ->where('performance.user_id',$id)
		            ->orderby('ph_id','desc')->get(); 
				//	dd(  $id);
		return $query;
	}
	public static function pe_evaluation_history($id){
        $query = 	PerformanceHistory::select('performance_history.*','performance.user_id')
		            ->join('performance','performance_history.performance_id','=','performance.performance_id')
		            ->where('performance.user_id',$id)
		            ->orderby('ph_id','desc')->get(); 
		return $query->toArray();
	}
    public static function getWeeklyStatus($user_id){
		$lastsun 			= strtotime('last Sunday');
        $query =WeeklyWorkingHour::where('user_id',$user_id)
		->where('date', '>=', $lastsun)
		->orderby('wrk_id','desc')->first();
		return $query;
	}
    Public static function getEmployee($empid){
		$query = DB::table('users')->select('users.*','performance.*',DB::raw('performance.preview+performance.creview+performance.tquality+performance.cquality+performance.pviolation+performance.cypviolation+performance.slaviolation+performance.wreport+performance.warning+performance.suspension+performance.awards+performance.ChallengeOfTheDay+performance.trialpperf+performance.servicecancellation as PE'),
	        	DB::raw('performance.goldenresponse+performance.treplies+performance.blogpost+performance.training+performance.interviews+performance.certifications+performance.seminars as IE'),
	        	DB::raw('performance.codeof+performance.ssmedia+performance.extracurricular as CE')
	        	)
	 		    ->join('performance','users.id','=','performance.user_id')
	            ->where('users.id',$empid)
	            ->orderby('performance.performance_id','desc')    		
		        ->limit(1)->get(); 
				$query =  json_decode( json_encode($query), true);

		return $query;
	}

	public static function getPendingHours($workId,$user_id){
		$lastsun 			= strtotime('last Sunday');
		$query =	WeeklyWorkingHour::select("pending_hrs")
		->where('date', '>=', $lastsun)
		              ->where('wrk_id',$workId)->first();
					  $userdetails = User::where('id',$user_id)->first();

					  if(!$query){
						if($userdetails->desgn_id == 1) {
							$fix_pend_minutes  = 178200;
						} else {
							$fix_pend_minutes  = 148500;
						}
						$wrkd_hrs = new WeeklyWorkingHour;
						$wrkd_hrs->user_id = $user_id;
						$wrkd_hrs->extra_hrs = 0;
						$wrkd_hrs->flexi_hrs =0;
						$wrkd_hrs->hrs_worked =0;
						$wrkd_hrs->pending_hrs = $fix_pend_minutes;

						$wrkd_hrs->date = strtotime('now');
						$wrkd_hrs->save();
						$query =	WeeklyWorkingHour::select("pending_hrs")
						->where('date', '>=', $lastsun)->where('wrk_id',$wrkd_hrs->wrk_id)->first();
						return $query->pending_hrs;

					  }
							  
		return $query->pending_hrs;
	}
    
	public static function getPendingUser($workId){
		$query =	WeeklyWorkingHour::select("user_id")
		              ->where('wrk_id',$workId)->first();
		return $query->user_id;
	}
    
	public static function updateMandatory($workId,$fix_pend_minutes,$user_id){
		$lastsun 			= strtotime('last Sunday');

		$data = array(
			"pending_hrs" => $fix_pend_minutes
 			); 
		if($workId==1){	
		$query =	WeeklyWorkingHour::where('date', '>=', $lastsun)->where('user_id',$user_id)->first();
	    	//  dd( $query->wrk_id);
		$updated = WeeklyWorkingHour::where('wrk_id', $query->wrk_id)
		->update($data);
	}else{
		$updated = WeeklyWorkingHour::where('wrk_id', $workId)
		->update($data);
	}
		return $updated;
		}

		Public  static function history_ids($month_id,$uid){
			$a 		 = strtotime("01-".$month_id);	
			$nor 	 = date('Y-m-d',$a);
			$lastday = date('t',strtotime($nor));
			$time1   = date('d-m-Y',$a);
	//		$time2   = date($lastday."-".$month_id);
			$time2   = date($lastday."-".$month_id." 11:59 p\m");
	
			$query1=Performance::select('performance_id')->where('performance.user_id',$uid)
			->where('performance.date', '>=', strtotime($time1))
			->where('performance.date', '<=', strtotime($time2))
			->orderby('performance_id','desc')->get(); 	
			
	//		$this->db->select('*');
	//		$this->db->from('comments');
	//		$this->db->where('user_id',$uid);
	//		$this->db->where('comments.time >=', strtotime($time1));
	//		$this->db->where('comments.time <=', strtotime($time2));
	//		$query2 = $this->db->get();
	//		$res['comments'] = $query2->result_array();
			return $query1->toarray();; 
	
	//		return $this->db->last_query();
	
		}
		
		Public static function history($per_id,$month_id){
			$a 		 = strtotime("01-".$month_id);	
			$nor 	 = date('Y-m-d',$a);
			$lastday = date('t',strtotime($nor));
			$time1   = date('d-m-Y',$a);
	//		$time2   = date($lastday."-".$month_id);
			$time2   = date($lastday."-".$month_id." 11:59 p\m");
			
	        $query =PerformanceHistory::where('performance_id',$per_id)
			->where('time', '>=', strtotime($time1))
			->where('time' ,'<=', strtotime($time2))
			->orderby('ph_id','desc')->get();
			return $query->toarray();;
			
		}
		
		Public static function getting_comments($user_id,$month_id){
		    $a 		 = strtotime("01-".$month_id);	
			$nor 	 = date('Y-m-d',$a);
			$lastday = date('t',strtotime($nor));
			$time1   = date('d-m-Y',$a);
			$time2   = date($lastday."-".$month_id." 11:59 p\m");
			
			$query2 =Comment::where('user_id',$user_id)
			->where('comments.time','>=', strtotime($time1))
			->where('comments.time', '<=', strtotime($time2))
			->orderby('com_id','desc')->get();
			return $query2->toarray();
			
		}
}
