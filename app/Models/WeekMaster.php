<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekMaster extends Model
{
    use HasFactory;
    protected $table = 'week_master';
    public $timestamps = false;
}
