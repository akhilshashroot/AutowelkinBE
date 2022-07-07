<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftWeekManager extends Model
{
    use HasFactory;
    protected $table = 'shift_week_manager';
    public $timestamps = false;
}
