<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User;
use App\Models\Designation;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens; 

    protected $table = 'admin_login';
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'password','role','status','team_id','month_year',
    ];

    //	Get all employees
	Public function getEmployee_daily(){
        $employees = User::orderBy('fullname','asc')->get();
		return $employees;
	}
    public function getCurrentEmployees(){
        $employees = User::where('team_id','!=',42)->orderBy('fullname','asc')->get();
        //42=> resigned employee
		return $employees;
	}
    Public function viewalldesignation(){
        $designation = Designation::orderBy('desg_id','asc')->get();
		return $designation;
	}
}
