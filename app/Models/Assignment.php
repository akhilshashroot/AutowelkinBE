<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Assignment extends Model
{
    use HasFactory;
    protected $table = 'assignments';
    public $timestamps = false;
    protected $primaryKey = 'asgnmnt_id';
    protected $fillable = [
        'title', 'body', 'creator_id','assign_to',
        'comments','status','period','time_stamp',
        'date','task_attachment'
    ];

    public static function getTaskList($admin_id){
        $query = Assignment::select('assignments.*','users.fullname as assignee')
       ->Join('users','users.id','=','assignments.assign_to')

        ->where('creator_id',$admin_id)
       // ->orderby('assignments.status','asc')
        ->orderby('assignments.asgnmnt_id','desc')->get();

        return $query;
}    //     //,DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=4 AND lv_status=1 ) as LOP')

    public static function getTasklistOthers(){
        $query = Assignment::select('assignments.*','users.fullname',DB::raw('(SELECT fullname from users WHERE id=creator_id) as assigner'))
        ->Join('users','users.id','=','assignments.assign_to')

        ->where('creator_id','!=',1)
        ->orderby('assignments.asgnmnt_id','desc')
        ->orderby('assignments.status','asc')->get();

        return $query;
    }

    Public static function getcompleted($id)
	{		
		$query =Assignment::select(DB::raw('COUNT(*) as count'))
                   ->where('assign_to',$id)
                   ->where('status',1)->get();
        return $query;
        

    }

    Public static function getpending($id)
	{		
		$query =Assignment::select(DB::raw('COUNT(*) as count'))
                       ->where('assign_to',$id)
                       ->where('status',0)->get();
        return $query;
        

    }
    public  static function getOwnTaskList($user_id){
        $query =Assignment::leftjoin('users','users.id','=','assignments.assign_to')
                           ->where('assign_to',$user_id)
                           ->orderby('asgnmnt_id','desc')
                           ->orderby('status','asc')->get();
        return $query->toArray();
    }

}
