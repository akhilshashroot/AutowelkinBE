<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\WeekMaster;
use App\Models\Team;
use App\Models\ShiftSwap;

class ShiftRecord extends Model
{
    use HasFactory;
    protected $table = 'shift_records';
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function week()
    {
        return $this->hasOne(WeekMaster::class,'id','day');
    }
    public function team()
    {
        return $this->hasOne(Team::class,'team_id','team_id');
    }
    public function swap()
    {
        return $this->hasOne(ShiftSwap::class,'shift_id','id');
    }
}
