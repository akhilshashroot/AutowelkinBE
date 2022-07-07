<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MonthlyActivity;

class RepeatMonthlyData extends Model
{
    use HasFactory;
    protected $table = 'repeat_monthly_data';
    public $timestamps = false;
    protected $primaryKey = 'md_id';
    public function activity()
    {
        return $this->hasOne(MonthlyActivity::class,'ma_id','monthly_id');
    }

    Public static function check_monthly_datas($user_id,$mid,$date){
		$d1 = strtotime(date("Y-m-01 0:0:0"));
		$d2 = strtotime(date("Y-m-t 12:59:59"));
		$query=RepeatMonthlyData::where('user_id',$user_id)->where('monthly_id',$mid)->where('md_date', '>=',$d1)->where('md_date','<=',$d2)->get();
//		return $this->db->last_query();
		return $query;
	}
    Public static function checkrow($mid,$user_id){
		$d1 = strtotime(date("Y-m-01 0:0:0"));
		$d2 = strtotime(date("Y-m-t 12:59:59"));
        $query=RepeatMonthlyData::where('monthly_id',$mid)
                         ->where('user_id',$user_id)
		                 ->where('md_date', '>=',$d1)
		                ->where('md_date', '<=',$d2)->get();
		return $query->toArray();
	}
}
