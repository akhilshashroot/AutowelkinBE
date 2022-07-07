<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class TicketDetails extends Model
{
    use HasFactory;
    protected $table = 'ticket_details';
    public $timestamps = false;
    protected $fillable = [
        'att_id', 'user_id', 'ticket_id','response','sla','created','is_active'
    ];

    public static function get_submited_ticket_details($user_id, $att_id){
        $date=date('d.m.Y',strtotime("-1 days"));
		$query = DB::table('ticket_details')->where('att_id', $att_id)
			->where('user_id', $user_id)
            ->where('created', '>=', $date)
			->orderby('id', 'desc')->get();
         //   dd($query);
		return $query;
	}

    public static function get_ticket_details($user_id, $att_id, $date, $from_flag=""){
	
        $query = TicketDetails::where('att_id', $att_id)
				->where('user_id', $user_id)->get();
		//}
		return $query;
	}
}
