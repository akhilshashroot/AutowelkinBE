<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShiftWeekManager;
use App\Models\ShiftRecord;

class ShiftWeekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function getweeks(Request $request,$team_id) {
        $weeks = ShiftWeekManager::select('date_from','date_to','id')->where('team_id', $team_id)->get();
        return response()->json([
            'data' => $weeks,
            'message' => 'Success'
        ], 200);
    }
    public function loadPreviousShift(Request $request,$week_id) {
        $result = ShiftRecord::with('user','week','swap')->where('week_id',$week_id)->get();
        $i = 0;
        
        $shiftarray = array();
        foreach($result as $val) {
            $shiftdata['shift'] = $val->shift;
            $shiftdata['users'] = $val->users;
            if($val->swap_user) {
                $swapdata = $val->swap->firstWhere('is_active', 1);
                $shiftdata[$val->shift]['swap_user'] = $swapdata->swap_user;
                $shiftdata[$val->shift]['swap_date'] = $swapdata->swap_date;
            }
            $comment[$val->week->day]['comment'] = $val->week->comment;
            $i++;
            if(isset($shiftarray[$val->week->day])) {
                if(count($shiftarray[$val->week->day]) == 0) {
                    $shiftarray[$val->week->day] = array();
                }
            } else {
                $shiftarray[$val->week->day] = array();
            }
            array_push($shiftarray[$val->week->day],$shiftdata);
            unset($shiftdata[$val->shift]);
            $created_by = isset($val->user)?$val->user->fullname:"";
        }
        $data['created_by'] = isset($created_by)?$created_by:"";
        $data['shiftdata'] = $shiftarray;
        $data['comment'] = isset($comment)?$comment:"";
        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ], 200);
    }
}
