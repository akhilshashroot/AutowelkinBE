<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class linkedinNotify extends Model
{
    use HasFactory;
    protected $table = 'linkedin_notify';
    protected $primaryKey = 'not_id';
    public $timestamps = false;
    protected $fillable = [
        'not_user', 'not_status','not_name'
    ];
}
