<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WeeklyActivity;

class WeeklyData extends Model
{
    use HasFactory;
    protected $table = 'weekly_data';
    public $timestamps = false;
    protected $primaryKey = 'wd_id';
    public function activity()
    {
        return $this->hasOne(WeeklyActivity::class,'wa_id','weekly_id');
    }


    Public static function check_weekrow_in_data($wactvity,$lastsun){
		$query =WeeklyData::where('wd_date','>=',$lastsun)
                            ->where('weekly_id',$wactvity['weekly_id'])
		                    ->where('user_id',$wactvity['user_id'])->get();
		return $query->toArray();
	}

    Public static function update_w_status($weekData,$week_id){
		$this->db->where('wd_id',$week_id);
		$query = $this->db->update('weekly_data',$weekData);
		if($query==1){
			return(1);
		}
		else{
			return(0);
		}
	}
}
