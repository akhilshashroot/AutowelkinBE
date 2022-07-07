<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\AttendanceLog;
use App\Models\User;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $user_id = $request->user_id;
        $month = $request->month_pick_attendancedat;
        if(!$month) {
            $month_id   = date('m-Y');
			$month_name = date('F Y');
        } else {
            $month_id   = $month;
			$m          = substr($month_id, 0, 2);
			$y          = substr($month_id, 2, 4); 
			$f_month    = "01-".$m."-".$y;
			$str_fmonth = strtotime($f_month);
			$month_name = date('F Y',$str_fmonth);
			$month_id   = $m."-".$y;
        }
        $attendanceData = array();
        $attendance_det = AttendanceLog::where('user_id',$user_id)->where('punchin_date', 'like', '%' . $month_id . '%')->orderBy('att_id','desc')->get();
        
        $i = 0;
        foreach($attendance_det as $val) {
            $attendanceData[$i]['att_id'] = $val->att_id;
            $attendanceData[$i]['user_id'] = $val->user_id;
            if($val->punchin) {
                $attendanceData[$i]['punchin'] = date('d-m-Y h:i a',$val->punchin);
            } else {
                $attendanceData[$i]['punchin'] = "";
            }
            if($val->punchout) {
                $attendanceData[$i]['punchout'] = date('d-m-Y h:i a',$val->punchout);
            } else {
                $attendanceData[$i]['punchout'] = "";
            }
            
            if($val->work_loc = '0') {
                $attendanceData[$i]['work_loc'] = 'Regular';
            }
            if($val->work_loc = '1') {
                $attendanceData[$i]['work_loc'] = 'Swap Shift';
            }
            if($val->work_loc = '1') {
                $attendanceData[$i]['work_loc'] = 'Swap Shift';
            }
            if($val->work_loc = '2') {
                $attendanceData[$i]['work_loc'] = 'Home Login';
            }
            if($val->work_loc = '3') {
                $attendanceData[$i]['work_loc'] = 'Extra Hours';
            } else {
                $attendanceData[$i]['work_loc'] = 'Project';
            }
            if($val->worked_time) {
                $work = round(($val->worked_time/60),2);
                $attendanceData[$i]['worked_time'] = date('H:i', mktime(0, $work))." Hrs";
            } else {
                $attendanceData[$i]['worked_time'] = "";
            }
            
            if($val->total_break) {
                $brk = $val->total_break;
                $brk = $brk/60;
                $brk_rem = $brk%60;
                $brk_rem = round($brk_rem);
                $brk = ($brk - $brk_rem)/60;
                $brk = round($brk);
                $attendanceData[$i]['total_break'] = $brk." hrs".$brk_rem." minutes";
            } else {
                $attendanceData[$i]['total_break'] = "";
            }
            $attendanceData[$i]['work_report'] = unserialize($val->work_report);
            $attendanceData[$i]['punchin_ip'] = $val->punchin_ip;
            $attendanceData[$i]['punchout_ip'] = $val->punchout_ip;
            $attendanceData[$i]['punchin_date'] = $val->punchin_date;
            $attendanceData[$i]['att_status'] = $val->att_status;
            $attendanceData[$i]['sla_violation'] = $val->sla_violation;
            $attendanceData[$i]['others'] = $val->others;
            $i++;
        }
        return response()->json([
            'data' => $attendanceData,
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
    public function destroy($id)
    {
        //
    }
}
