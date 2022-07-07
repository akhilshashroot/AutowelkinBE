<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendance';
    protected $primaryKey = 'at_id';
    protected $fillable = [
        'user_id','at_timing', 'at_month'
    ];
    public $timestamps = false;
}
