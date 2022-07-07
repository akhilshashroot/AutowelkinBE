<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use App\Models\DeskImage;
use App\Models\DailyActivity;
use App\Models\Department;
use App\Models\User;
use App\Models\MonthlyData;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }
    public function daily_datas(Request $request,$user_id,$month) {
        if($month ==''){
			$month_id   = date('m-Y');
			$day        = date('j');
			$mon_4Wrpt  = date('d-m-Y');
			$mon_4shot  = date('mY');
			$month      = date('d-m-Y');
		} else {
            $m = (explode("-",$month)[1]);

			$y = (explode("-",$month)[2]);

			$month_id = $m."-".$y;

			$day = (explode("-",$month)[0]);

			$day = (int)$day;	

			$mon_4Wrpt = $month;

			$mon_4shot = $m.$y;

			$month = $month;
        }
        $attendance_det = AttendanceLog::with('user')->where('user_id',$user_id)->where('punchin_date',$month)->orderBy('att_id','desc')->get();
        $attendanceArr = array();
        if($attendance_det->count()) {
            $i = 0;
            foreach($attendance_det as $attendance) {
                $attendanceArr[$i]['att_id'] = $attendance->att_id;
                $attendanceArr[$i]['user_id'] = $attendance->user_id;
                if($attendance->punchin) {
                    $attendanceArr[$i]['punchin'] = date('d-m-Y h:i a',$attendance->punchin);
                } else {
                    $attendanceArr[$i]['punchin'] = "";
                }
                if($attendance->punchout) {
                    $attendanceArr[$i]['punchout'] = date('d-m-Y h:i a',$attendance->punchout);
                } else {
                    $attendanceArr[$i]['punchout'] = "";
                }
                if($attendance->work_loc == '0') {
                    $attendanceArr[$i]['work_loc'] = 'Regular';
                }
                else if($attendance->work_loc == '1') {
                    $attendanceArr[$i]['work_loc'] = 'Swap Shift';
                }
                else if($attendance->work_loc == '1') {
                    $attendanceArr[$i]['work_loc'] = 'Swap Shift';
                }
                else if($attendance->work_loc == '2') {
                    $attendanceArr[$i]['work_loc'] = 'Home Login';
                }
                else if($attendance->work_loc == '3') {
                    $attendanceArr[$i]['work_loc'] = 'Extra Hours';
                } else {
                    $attendanceArr[$i]['work_loc'] = 'Project';
                }
                if($attendance->worked_time) {
                    $work = round(($attendance->worked_time/60),2);
                    $attendanceArr[$i]['worked_time'] = date('H:i', mktime(0, $work))." Hrs";
                } else {
                    $attendanceArr[$i]['worked_time'] = "";
                }
                if($attendance->total_break) {
                    $brk = $attendance->total_break;
                    $brk = $brk/60;
                    $brk_rem = $brk%60;
                    $brk_rem = round($brk_rem);
                    $brk = ($brk - $brk_rem)/60;
                    $brk = round($brk);
                    $attendanceArr[$i]['total_break'] = $brk." hrs".$brk_rem." minutes";
                }
                $attendanceArr[$i]['work_report'] = unserialize($attendance->work_report);
                $attendanceArr[$i]['punchin_ip'] = $attendance->punchin_ip;
                $attendanceArr[$i]['punchout_ip'] = $attendance->punchout_ip;
                $attendanceArr[$i]['punchin_date'] = $attendance->punchin_date;
                $attendanceArr[$i]['att_status'] = $attendance->att_status;
                $i++;
                $dept_id = $attendance->user->dep_id;
            }
            $daily_activities = DailyActivity::where('dep_id',$dept_id)->get();
        } else {
            $daily_activities = "";
        }
        $data['attendance_det'] = $attendanceArr;
        $data['daily_activities'] = $daily_activities;
        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ], 200);
    }
    public function getjd(Request $request,$dep_id) {
        /*$validated = $request->validate([
            'dep_id' => 'required',
        ]);*/
        $data['jd'] = Department::where('dep_id',$dep_id)->get('job_desc');
        $data['daily_activities'] = DailyActivity::where('dep_id',$dep_id)->get();
        if($data) {
            return response()->json([
                'data' => $data,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
    }
    public function changejd(Request $request) {
        $validated = $request->validate([
            'dep_id' => 'required',
            'jd_desc' => 'required',
        ]);
        $department = Department::find($validated['dep_id']);
        if(!$department){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $department->job_desc =$validated['jd_desc'];
        $department->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
    }
    public function add_new_act(Request $request) {
        $validated = $request->validate([
            'daily_activity' => 'required',
            'dep_id' => 'required',
            'field_type_id' => 'required'
        ]);
        $dailyactivity = new DailyActivity;
        $dailyactivity->dep_id = $validated['dep_id'];
        $dailyactivity->daily_act = $validated['daily_activity'];
        $dailyactivity->status = 0;
        $dailyactivity->field_type = $validated['field_type_id'];
        $result = $dailyactivity->save();
        $userids = User::where('dep_id',$validated['dep_id'])->get();
        $daysofthemonth = date('d',strtotime('last day of this month'));
        for($i=1;$i<=$daysofthemonth;$i++){
            $ActivityArray[$i]['status']=0;
        }
        if(isset($userids)) {
            foreach($userids as $user) {
                $monthlydata = new MonthlyData;
                $monthlydata->daily_activity_ids = $dailyactivity->daily_act_id;
                $monthlydata->user_id = $user->id;
                $monthlydata->activity_date = strtotime('now');
                $monthlydata->activity_array = serialize($ActivityArray);
                $result1 = $monthlydata->save();
            }
        }
        if(!$result){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        } else {
            return response()->json([
                'status' => true,
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
        $result = DailyActivity::where('daily_act_id',$id)->delete();
        $result1 = MonthlyData::where('daily_activity_ids',$id)->delete();
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
