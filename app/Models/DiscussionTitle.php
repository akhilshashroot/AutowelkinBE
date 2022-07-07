<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class DiscussionTitle extends Model
{
    use HasFactory;
    protected $table = 'discussion_title';
    public $timestamps = false;
    protected $dates = ['created'];

    protected $fillable = [
        'title', 'user_id','subtitle_id', 'type','is_active','created'

    ];

    public static function get_subtitles($dt_id){
        $query=DiscussionTitle::select('id',DB::raw('SUBSTR(title, 1, 20) as sub_topic'))
			->where('subtitle_id', $dt_id)
			->where('type', 'sub')
			->where('is_active', 1)->get();
		return $query;
	}

    public  static function get_discussion_details($d_id){
        $query=DiscussionTitle::select('discussion_title.title', DB::raw('DATE_FORMAT(discussion_title.created, "%M %d %Y %h:%i %p") as date'), 'u.fullname', 'u.emp_id', 'al.name', 'discussion_title.id')
			->leftjoin('users as u', 'u.id', '=' ,'discussion_title.user_id')
			->leftjoin('admin_login as al', 'al.id','=', 'discussion_title.user_id')
			->where('discussion_title.id', $d_id)->get();
		return $query;
	}

}
