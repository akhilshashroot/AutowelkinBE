<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectRoom;

class ProjectRoomUser extends Model
{
    use HasFactory;
    protected $table = 'project_room_users';
    protected $primaryKey = 'pru_id';
    public $timestamps = false;

    public function project()
    {
        return $this->hasOne(ProjectRoom::class,'pr_id','pr_id');
    }
}
