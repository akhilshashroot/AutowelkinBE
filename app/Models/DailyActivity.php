<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    use HasFactory;
    protected $table = 'daily_activities';
    public $timestamps = false;
    protected $primaryKey = 'daily_act_id';


    Public static function get_ids_daily($user_id,$dep_id){
		$query = DailyActivity::select('daily_act_id')
		             ->where('dep_id',$dep_id)->get();
		return $query;
		
	}
	Public static function get_Daily_Acts($user_id,$dep_id){
		//$d1 = strtotime(date("Y-m-01 0:0:0"));
		//$d2 = strtotime(date("Y-m-31 12:59:59"));
		$query = DailyActivity::
		//$this->db->join('monthly_data', 'daily_activities.daily_act_id = monthly_data.daily_activity_ids');
		//$this->db->where('monthly_data.user_id',$user_id);
	          where('dep_id',$dep_id)
		//$this->db->where('monthly_data.activity_date >=',$d1);
		//$this->db->where('monthly_data.activity_date <=',$d2);
		->orderby('daily_act_id','ASC')->get();
		return $query;
//		return $this->db->last_query();
	}

}
