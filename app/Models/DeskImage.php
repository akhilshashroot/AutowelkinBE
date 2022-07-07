<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeskImage extends Model
{
    use HasFactory;
    protected $table = 'desk_images';
    public $timestamps = false;
	protected $primaryKey = 'di_id ';
}
