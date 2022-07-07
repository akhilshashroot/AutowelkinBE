<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthlyActivity;
use App\Models\RepeatMonthlyData;
use App\Models\User;

class MonthlyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$user_id,$month)
    {
        if($month ==''){
			$month_id   = date('m-Y');
			$month_name = date('M Y');
		} else {
            $month_id   = $month;
			$m          = substr($month_id, 0, 2);
			$y          = (explode("-",$month)[1]);
			$month_id   = $m."-".$y;
			$f_month    = "01-".$m."-".$y;
			$str_fmonth = strtotime($f_month);
			$month_name = date('F Y',$str_fmonth);
        }
        $user = User::find($user_id);
        if($user->dep_id) {
            $dept_id = $user->dep_id;
            $a = strtotime("01-".$month_id);	
            $nor = date('Y-m-d',$a);
            $lastday = date('t',strtotime($nor));
            $time1 = date('d-m-Y',$a);
            $time2 = date($lastday."-".$month_id);
            $monthly_checklist = RepeatMonthlyData::with('activity')->where('user_id',$user_id)->where('md_date','>=', strtotime($time1))->where('md_date','<=', strtotime($time2))
            ->whereHas('activity', function($q) use ($dept_id) {
                $q->where('ma_field_type','0')->where('dep_id',$dept_id);
            })
            ->get();
            $monthly_workreport_act = RepeatMonthlyData::with('activity')->where('user_id',$user_id)->where('md_date','>=', strtotime($time1))->where('md_date','<=', strtotime($time2))
            ->whereHas('activity', function($q) use ($dept_id) {
                $q->where('ma_field_type','1')->where('dep_id',$dept_id);
            })
            ->get();
            $data['monthly_checklist'] = $monthly_checklist;
            $data['monthly_workreport_act'] = $monthly_workreport_act;
            return response()->json([
                'data' => $data,
                'message' => 'Success'
            ], 200);
        }
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
        $validated = $request->validate([
            'dep_id' => 'required',
            'ma_activity' => 'required',
            'ma_field_type' => 'required',
        ]);
        $monthly_activity = new MonthlyActivity;
        $monthly_activity->ma_date = strtotime('now');
        $monthly_activity->dep_id = $validated['dep_id'];
        $monthly_activity->ma_activity = $validated['ma_activity'];
        $monthly_activity->ma_field_type = $validated['ma_field_type'];
        $result = $monthly_activity->save();
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
        $result = MonthlyActivity::where('ma_id',$id)->delete();
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
    public function getmonthlyactivity(Request $request,$dept_id) {
        $monthlyactivity = MonthlyActivity::where('dep_id',$dept_id)->orderBy('ma_id','desc')->get();
        if($monthlyactivity) {
            return response()->json([
                'data' => $monthlyactivity,
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
