<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin;

class Exam extends Model
{
    use HasFactory;
    protected $table = 'exam';
    public $timestamps = false;
    protected $fillable = [
        'examiner', 'candidate_name', 'position','candidate_email',
        'candidate_phone','exam_date','exam_date_str','examiners_details',
        'examiners','resume','notice_period','current_salary','expected_salary',
        'comments','status','joining_date','joining_date_str','creator','is_active','created',
        'mode','priority','time','comment_array'
    ];

    public  static function checkMailUnique($email){

			$query =Exam::where('candidate_email',$email)->first();       
			return $query;
	}

    public static function get_examiners_details($examiners_id){

        $query  = User::where('id', $examiners_id)->first();
		return $query;
	}
    public static function get_creator_interview($creator_id){
	
        $query = Admin::where('id',$creator_id)->first();
		return $query;
	}
}
