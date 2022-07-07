<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Discussion extends Model
{
    use HasFactory;
    protected $table = 'discussion';
    public $timestamps = false;
    protected $fillable = [
        'dst_id', 'd_id','discussion', 'user_id','created'
    ];

    Public static function getall($id){
		$query =User::where('id',$id)->first();
		return $query;
	}
}

