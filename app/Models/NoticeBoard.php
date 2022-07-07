<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    use HasFactory;
    protected $table = 'notice_board';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'notice_id','color','type','team_id','deps_id','created','is_active'
    ];
}
