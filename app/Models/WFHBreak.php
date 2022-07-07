<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceLog;

class WFHBreak extends Model
{
    use HasFactory;
    protected $table = 'wfh_break';
    public $timestamps = false;
    public function attendance()
    {
        return $this->hasOne(AttendanceLog::class,'att_id','p_id');
    }
}
