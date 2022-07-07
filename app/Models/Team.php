<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $table = 'team';
    protected $primaryKey = 'team_id';
    public $timestamps = false;
    protected $fillable = [
        'name', 'mail_ids', 'have_phpbb ','phpbbUsername','phpbbPassword'
    ];
}
