<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class JdSkillUpdater extends Model
{
    use HasFactory;
    protected $table = 'jd_skill_updater';
    public $timestamps = false;
    protected $primaryKey = 'skill_id';

	public static function getSkillSets($user_id){
        $query=JdSkillUpdater::where('user_id',$user_id)
		    ->orderby('skill_id', 'desc')->get();
		if($query){
               return (object)['status' => true, 'data' => $query];
           }else{
               return (object)['status' => false, 'message' => 'Sorry no records available!'];
           }
		
	}
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
