<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\AttendanceLog;
use DB;
class Requst extends Model
{
    use HasFactory;
    protected $table = 'request';
    public $timestamps = false;
	protected $primaryKey = 'lv_id ';
	protected $fillable = [
        'lv_date', 'lv_no','user_id','lv_purpose','lv_type','lv_aply_date','approvedby','lv_status',
		'lv_date_to','lv_img','appr_person','lv_hrs_status','is_admin'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public static function NoHolidaysLeave($user_id,$new_joining_date){
		$query =Requst::select(DB::raw('sum(lv_no) as total'))
		        ->where('user_id',$user_id)
	            ->where('lv_type',7)
	            ->where('lv_status',1)
		        ->where('lv_date','>=',$new_joining_date)->get();
		return $query; 	
	}

    Public static function Noofcasualleaves($user_id,$new_joining_date){			
        $query =Requst::select(DB::raw('sum(lv_no) as total'))
             ->where('user_id',$user_id)
            ->where('lv_type',1)
            ->where('lv_date','>=',$new_joining_date)
            ->where('lv_status',1)->get();				
         return $query;        
    }
    Public static function Noofsickleaves($user_id,$new_joining_date){		
        $query =Requst::select(DB::raw('sum(lv_no) as total'))
                      ->where('user_id',$user_id)	
                      ->where('lv_type',2)
                      ->where('lv_date','>=',$new_joining_date)
                      ->where('lv_status',1)->get(); 
         return $query;  
    }

    
	public static function NoofWFH($session_id,$getLeaveResetDate){
			//$dates = $this->get_year_start_end();
			$query =AttendanceLog::select(DB::raw('count(att_id) as total'))
				->where('user_id', $session_id)
				->where('work_loc', 2)
				->where('punchin', '>=', $getLeaveResetDate)->get();
                return $query;
		}

	Public static function NoofWFHfromRequest($user_id,$getLeaveResetDate){
        $query =Requst::select(DB::raw('sum(lv_no) as total'))
	                 ->where('user_id',$user_id)	
			         ->where('lv_type',3)	
			         ->where('lv_date','>=',$getLeaveResetDate)	
			         ->where('lv_date','<=',1547445316)	
			         ->where('lv_status',1)->get(); 				
			return $query;	
		}	
    	Public static function NoofLOP($user_id,$getLeaveResetDate){
            $query =Requst::select(DB::raw('sum(lv_no) as total'))
			        ->where('user_id',$user_id)
			        ->where('lv_type',4)	
			        ->where('lv_date','>=',$getLeaveResetDate)		
			        ->where('lv_status',1)->get(); 		
			return $query;
			
		}	

		public static function NooSwap($user_id,$getLeaveResetDate){
            $query =Requst::select(DB::raw('sum(lv_no) as total'))
			        ->where('user_id',$user_id)	
			        ->where('lv_type',5)		
			        ->where('lv_date','>=',$getLeaveResetDate)		
			        ->where('lv_status',1)->get(); 			
			return $query;
		}
	
}
