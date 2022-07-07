<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requst;
use App\Models\Department;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestApprove;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$data= Auth::user();
        if($request->role == '4') {
            $departmentId = Department::where('dep_name','Solutions')->first('dep_id');
            $id = $departmentId->dep_id;
            $requests = Requst::with('user')->whereHas('user', function($q) use ($id) {
                $q->where('dep_id', $id);
            })->orderBy('lv_id','desc')->get();
        } else {
            $requests = Requst::with('user')->orderBy('lv_id','desc')->get();
        }
        $i = 0;
        foreach($requests as $val) {
            if(isset($val->user)) {
                $dat[$i]['fullname'] = $val->user->fullname;
            } else {
                $dat[$i]['fullname'] = '';
            }
            $dat[$i]['lv_id'] = $val->lv_id;
            $dat[$i]['consentof'] = $val->approvedby;
            $dat[$i]['lv_purpose'] = $val->lv_purpose;
            $dat[$i]['lv_status'] = $val->lv_status;
            $dat[$i]['lv_aply_date'] = date('d-m-Y',$val->lv_aply_date);
            $dat[$i]['lv_date'] = date('d-m-Y',$val->lv_date);
            $dat[$i]['lv_file'] = ($val->lv_img)?env('APP_URL').'storage/leavefiles/'.$val->lv_img:"";
            if(!empty($val->lv_date_to)){
                $dat[$i]['lv_date_to'] = date('d-m-Y',$val->lv_date_to);
            }
            $leave_type =  $val->lv_type;
            switch($leave_type){
                case 1: $dat[$i]['lv_title']='CL';
                    break;
                case 2:$dat[$i]['lv_title']='ML';
                    break;
                case 3:$dat[$i]['lv_title']='WFH';
                    break;
                case 4:$dat[$i]['lv_title']='LOP';
                    break;
                case 5:$dat[$i]['lv_title']='SW';
                    break;
                case 7:$dat[$i]['lv_title']='HOLIDAY';
                    break;
            }
            $i++;
        }
        return response()->json([
            'data' => $dat,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $result = Requst::where('lv_id',$id)->delete();
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
    }
    public function approve(Request $request,$id) {
        $result = Requst::where('lv_id',$id)->update(['lv_status'=>1]);
        $request = Requst::with('user')->where('lv_id',$id)->first();
        switch($request->lv_type){

			case 1:
                $request_type = "Casual Leaves";

			break;

			case 2:
                $request_type = "Medical Leaves"; 

			break;

			case 3:
                $request_type = "Work From Home";

			break;

			case 4:
                $request_type = "LOP";

			break;			

			case 5:
                $request_type = "Swap"; 

			break;

		}
        try {
            Mail::to($request->user->email)->send(new RequestApprove($request,$request_type));
        } catch (\Exception $e) {
			Log::info( "request approve mail:".$e->getMessage());
		}
        
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
    }
    public function reject(Request $request,$id) {
        $result = Requst::where('lv_id',$id)->update(['lv_status'=>2]);
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
    }
}
