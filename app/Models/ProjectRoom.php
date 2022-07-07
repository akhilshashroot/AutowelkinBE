<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRoom extends Model
{
    use HasFactory;
    protected $table = 'project_room';
    protected $primaryKey = 'pr_id';
    public $timestamps = false;
}
