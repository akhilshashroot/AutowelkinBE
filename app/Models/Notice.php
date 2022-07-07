<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;
    protected $table = 'notice';
    public $timestamps = false;
    protected $fillable = [
        'notice', 'created','is_active'
    ];

    public static function create_notice($notice){
        $insert_id=Notice::create(["notice"=>$notice]);
		return $insert_id->id;
	}

    
	public static  function update_notice_post($notice, $notice_id){
		$updated = Notice::find($notice_id);  
        if(!$updated){
        return false;
        }      
        $updated->notice =$notice;
        $updated->save();
    	return true;
	
	}

	public static function delete_notice_bord_data($notice_id){
		$deleted = NoticeBoard::where('notice_id', $notice_id)
			->delete();

		if($deleted){
			return true;
		}else{
			return false;
		}
	}

    public static function get_notice_board_details($notice_id, $type){

        $query = NoticeBoard::select('notice_board.*', 'n.notice')
            ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
			->where('notice_board.is_active', 1)
			->where('notice_board.type', $type)
			->where('notice_board.notice_id', $notice_id)->get();

	
		return $query;
	}

    public static function get_notice_board_team_details($notice_id, $type){

        $query = NoticeBoard::select('notice_board.*', 'n.notice')
            ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
			->where('notice_board.is_active', 1)
			->where('notice_board.type', $type)
			->where('notice_board.notice_id', $notice_id)->groupBy('notice_board.team_id')
			->get();

	
		return $query;
	}
	public static function get_notice_board_department_details($notice_id, $type){

        $query = NoticeBoard::select('notice_board.*', 'n.notice')
            ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
			->where('notice_board.is_active', 1)
			->where('notice_board.type', $type)
			->where('notice_board.notice_id', $notice_id)->groupBy('notice_board.deps_id')
			->get();

	
		return $query;
	}
}
