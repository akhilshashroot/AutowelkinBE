<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WeeklyData;

class WeeklyActivity extends Model
{
    use HasFactory;
    protected $table = 'weekly_activity';
    protected $primaryKey = 'wa_id';
    public $timestamps = false;
}
