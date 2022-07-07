<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;

class AdminController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }
    /**
     * show dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Auth::user();

		$data['employees'] = Admin::getEmployee_daily();
		$data['current_employees'] = Admin::getCurrentEmployees();

		$data['designation_det'] = Admin::viewalldesignation();
        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ], 200);
        //dd($data);
        //return view('admin.admin',compact('data'));
    }
}
