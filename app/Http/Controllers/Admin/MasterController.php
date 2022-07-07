<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NoticeBoard;
use App\Models\Team;
use App\Models\Department;
class MasterController extends Controller
{
    
    /**
     *  listing of the employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_employees(Request $request)
    {
        $employees = User::select('id','fullname as name')->where('team_id','!=',42)->orderBy('fullname','asc')->get();
        return response()->json([
            'data' => $employees ,
        ]);
    }

       
    /**
     *  listing of the teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_team(Request $request)
    {
        $teams = Team::select('team_id','name')->orderBy('name','asc')->get();
        return response()->json([
            'data' => $teams ,
        ]);
    }

       
    /**
     *  listing of the department.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_department(Request $request)
    {
        $departments = Department::select('dep_id','dep_name')->orderBy('dep_name','asc')->get();
        return response()->json([
            'data' => $departments ,
        ]);
    }
}
