<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WeeklyActivity;
use App\Models\User;
use App\Models\WeeklyData;

class WeeklyReportController extends Controller
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
            /*$full_weeklyAct = WeeklyActivity::with('weeklydata')->where('wa_field_type','0')->where('dep_id',$user->dep_id)
            ->whereHas('weeklydata', function($q) use ($time1,$time2,$user_id) {
                $q->where('user_id',$user_id)->where('wd_date','>=', strtotime($time1))->where('wd_date','<=', strtotime($time2));
            })->get();*/
            $full_weeklyAct = WeeklyData::with('activity')->where('user_id',$user_id)->where('wd_date','>=', strtotime($time1))->where('wd_date','<=', strtotime($time2))
            ->whereHas('activity', function($q) use ($dept_id) {
                $q->where('wa_field_type','0')->where('dep_id',$dept_id);
            })
            ->get();
            $full_weeklyRpt = WeeklyData::with('activity')->where('user_id',$user_id)->where('wd_date','>=', strtotime($time1))->where('wd_date','<=', strtotime($time2))
            ->whereHas('activity', function($q) use ($dept_id) {
                $q->where('wa_field_type','1')->where('dep_id',$dept_id);
            })
            ->get();
            $data['weekly_checklist'] = $full_weeklyAct;
            $data['weekly_report'] = $full_weeklyRpt;
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
            'wa_activity' => 'required',
            'wa_field_type' => 'required',
        ]);
        $weekly_activity = new WeeklyActivity;
        $weekly_activity->wa_date = strtotime('now');
        $weekly_activity->dep_id = $validated['dep_id'];
        $weekly_activity->wa_activity = $validated['wa_activity'];
        $weekly_activity->wa_field_type = $validated['wa_field_type'];
        $result = $weekly_activity->save();
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
        $result = WeeklyActivity::where('wa_id',$id)->delete();
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

    public function getweeklyactivity(Request $request,$dep_id) {
        $weeklyactivity = WeeklyActivity::where('dep_id',$dep_id)->orderBy('wa_id','desc')->get();
        if($weeklyactivity) {
            return response()->json([
                'data' => $weeklyactivity,
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
