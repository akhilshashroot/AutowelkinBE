<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventory';
    protected $primaryKey = 'inv_id';
    public $timestamps = false;
    protected $fillable = [
        'inv_serial','inv_type', 'inv_brand', 'inv_specs ','inv_team','inv_invoice','inv_id','GST'
    ];
}
