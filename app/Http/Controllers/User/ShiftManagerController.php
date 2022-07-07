<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShiftWeekManager;
use App\Models\WeekMaster;
use App\Models\ShiftRecord;
use App\Models\ShiftSwap;

class ShiftManagerController extends Controller
{
    public function getteam_members(Request $request,$team_id) {
        $members = User::select('fullname','id')->where('team_id',$team_id)->get();
        if($members->count()) {
            return response()->json([
                'data' => $members,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry no memebers'
            ], 200);
        }
    }
    public function createShift(Request $request) {
        $team_id = $request->team_id;
        $curr_date = date("y-m-d");
        $curr_day = date('l', strtotime($curr_date));
        $date_a['date_from'] = $this->getWeekStartDate($curr_date, $curr_day);
        $morning_shift = $request['morning_shift[]'];
		$evening_shift = $request['evening_shift[]'];
		$night_shift = $request['night_shift[]'];
		$off = $request['off[]'];
		$day = $request->day;
		$comment = $request->comment;
        $user_id = $request->user_id;
        if(!$comment) {
            $comment = '-';
        }
        $date = date_create($date_a['date_from']);
        date_add($date,date_interval_create_from_date_string("6 days"));
        $date_a['date_to'] = date_format($date,"Y-m-d");
		$date_a['team_id'] = $team_id;

        switch ($curr_day) {
			case 'Saturday':
				$date = date_create($curr_date);
				date_add($date,date_interval_create_from_date_string("2 days"));
			    $date = date_format($date,"Y-m-d");
                $weekId = $this->getWeekIdForPreview($team_id, $date);
                if(!$weekId) {
                    $shiftrecord = new ShiftWeekManager;
                    $shiftrecord->date_from = $date_a['date_from'];
                    $shiftrecord->date_to  = $date_a['date_to'];
                    $shiftrecord->team_id = $team_id;
                    $week_res = $shiftrecord->save();
                    $weekId = $shiftrecord->id;
                }
				break;

			case 'Sunday':
				$date = date_create($curr_date);
				date_add($date,date_interval_create_from_date_string("1 days"));
			    $date = date_format($date,"Y-m-d");
                $weekId = $this->getWeekIdForPreview($team_id, $date);
                $check_shift_status = $weekid->id;
                if(!$weekId) {
                    $shiftrecord = new ShiftWeekManager;
                    $shiftrecord->date_from = $date_a['date_from'];
                    $shiftrecord->date_to  = $date_a['date_to'];
                    $shiftrecord->team_id = $team_id;
                    $week_res = $shiftrecord->save();
                    $weekId = $shiftrecord->id;
                }
				break;
			default:
                $date = date_format($date,"Y-m-d");
                $weekId = $this->getWeekIdForPreview($team_id, $date);
                if(!$weekId) {
                    $shiftrecord = new ShiftWeekManager;
                    $shiftrecord->date_from = $date_a['date_from'];
                    $shiftrecord->date_to  = $date_a['date_to'];
                    $shiftrecord->team_id = $team_id;
                    $week_res = $shiftrecord->save();
                    $weekId = $shiftrecord->id;
                }
				break;
		}
        $order = $this->getShiftOrder($day);
        $shiftdaydata = WeekMaster::select('id')->where('week_id',$weekId)->where('day',$day)->where('order_by',$order)->first();
        if($shiftdaydata) {
            $shiftday = $shiftdaydata->id;
        } else {
            $shiftdayinsert = new WeekMaster;
            $shiftdayinsert->week_id = $weekId;
            $shiftdayinsert->day = $day;
            $shiftdayinsert->order_by = $order;
            $shiftdayinsert->comment = $comment;
            $shiftdayres = $shiftdayinsert->save();
            $shiftday = $shiftdayinsert->id;
        }
        $checkShiftRecord = ShiftRecord::select('id')->where('week_id', $weekId)->where('day', $shiftday)->get();
        if($checkShiftRecord->count()){
            return response()->json([
                'status' => false,
                'message' => 'Sorry, this shift already exists!'
            ], 200);
		}
        if($morning_shift){
			$shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'morning';
            //$shiftdata->users = implode(',',$morning_shift);
            $shiftdata->users = $morning_shift;
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}else{
			$shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'morning';
            $shiftdata->users = '-';
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}
        if($evening_shift){
            $shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'evening';
            //$shiftdata->users = implode(',',$evening_shift);
            $shiftdata->users = $evening_shift;
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}else{
			$shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'evening';
            $shiftdata->users = '-';
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}
        if($night_shift){
            $shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'night';
            //$shiftdata->users = implode(',',$night_shift);
            $shiftdata->users = $night_shift;
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}else{
			$shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'night';
            $shiftdata->users = '-';
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}
        if($off){
            $shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'off';
            //$shiftdata->users = implode(',',$off);
            $shiftdata->users = $off;
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}else{$shiftdata = new ShiftRecord;
            $shiftdata->week_id = $weekId;
            $shiftdata->shift = 'off';
            $shiftdata->users = '-';
            $shiftdata->team_id = $team_id;
            $shiftdata->created_by = $user_id;
            $shiftdata->day = $shiftday;
            $shiftdata->save();
		}
        return response()->json([
            'status' => true,
            'message' => 'Success'
        ], 200);
    }
    public function getWeeks(Request $request,$team_id) {
        $data = ShiftWeekManager::where('team_id',$team_id)->get();
        if($data) {
            return response()->json([
                'data' => $data,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry no weeks avaiilable'
            ], 200);
        }
    }
    public function loadPreviousShift(Request $request,$weekId,$user_id) {
        $shiftDay = WeekMaster::where('week_id', $weekId)->orderBy('order_by')->get();
        $data_a = [];
		$i = 0;
        foreach ($shiftDay as $row) {
            $j = 0;
            $data_a[$i]['day'] = $row->day;
			$data_a[$i]['comment'] = $row->comment;
			$data_a[$i]['id'] = $row->id;
            $data_a[$i]['shift'] = ShiftRecord::with('user')->where('week_id',$weekId)->where('day',$row->id)->get();
            foreach ($data_a[$i]['shift'] as $value) {
                if($user_id == $value->created_by) {
                    $data_a[$i]['own_shift'] = 1; 
                } else {
                    $data_a[$i]['own_shift'] = 0;
                }
                if($value->swap_user != 0){
					$data_a[$i]['shift'][$j]['swap'] = ShiftSwap::where('shift_id',$value->id)->where('is_active', 1)->get();
				}else{
					$data_a[$i]['shift'][$j]['swap'] = [];
				}
                $j++;
            }
            $i++;
        }
        if($data_a) {
            return response()->json([
                'data' => $data_a,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry no records available'
            ], 200);
        }
    }
    public function editShifts(Request $request,$id) {
        $users = $request->users;
        $user_id = $request->user_id;
        $shiftdata = ShiftRecord::where('id',$id)->first();
        if($shiftdata->created_by != $user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry! you do not have permission to edit this shift'
            ], 200);
        }
        $users_str = implode(",", $users);
        $curDateTime = date('Y-m-d h:i:s');
        $shiftdata->users = $users_str;
        $shiftdata->modified_by = $user_id;
        $shiftdata->modified = $curDateTime;
        $shiftdata->swap_user = 0;
        $result = $shiftdata->save();
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Fail'
            ], 200);
        }

    }
    public function swapShift(Request $request) {
         $shift = $request->shift;
         $swap = $request->swap;
         $shift_id = $request->shiftId;
         $swapDate = $request->swapDate;
         $swaping = $shift." swapped with ".$swap;
         $shiftswap = new ShiftSwap;
         $shiftswap->shift_id = $shift_id;
         $shiftswap->swap_user = $swaping;
         $shiftswap->swap_date = $swapDate;
         $res = $shiftswap->save();
         if($res) {
             $shiftrecord = ShiftRecord::where('id',$shift_id)->first();
             $shiftrecord->swap_user = 1;
             $shiftrecord->save();
             return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
         } else {
            return response()->json([
                'status' => false,
                'message' => 'Fail'
            ], 200);
         }
    }
    public function previewShift(Request $request,$team_id) {
        $date = date("Y-m-d");
		$day = date('l', strtotime($date));
        switch ($day) {
			case 'Saturday':
				$date = date_create($date);
				date_add($date,date_interval_create_from_date_string("2 days"));
			    $date = date_format($date,"Y-m-d");
				break;

			case 'Sunday':
				$date = date_create($date);
				date_add($date,date_interval_create_from_date_string("1 days"));
			    $date = date_format($date,"Y-m-d");
				break;
			
			default:
				$date = $this->getWeekStartDate($date, $day);
				break;
		}
        $weekId = ShiftWeekManager::where('date_from','<=',$date)->where('date_to','>=',$date)->where('team_id', $team_id)->first();
        if(empty($weekId)) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry! No records found'
            ], 200);
        }
        $shiftDay = WeekMaster::where('week_id', $weekId->id)->orderBy('order_by')->get();
        $data_a = [];
		$i = 0;
        foreach ($shiftDay as $row) {
            $j = 0;
            $data_a[$i]['day'] = $row->day;
			$data_a[$i]['comment'] = $row->comment;
			$data_a[$i]['id'] = $row->id;
            $data_a[$i]['shift'] = ShiftRecord::with('user')->where('week_id',$weekId->id)->where('day',$row->id)->get();
            foreach ($data_a[$i]['shift'] as $value) {
                if($value->swap_user != 0){
					$data_a[$i]['shift'][$j]['swap'] = ShiftSwap::where('shift_id',$value->id)->where('is_active', 1)->get();
				}else{
					$data_a[$i]['shift'][$j]['swap'] = [];
				}
                $j++;
            }
            $i++;
        }
        if($data_a) {
            return response()->json([
                'data' => $data_a,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry no records available'
            ], 200);
        }
    }
    public function deleteSwap(Request $request,$id,$user_id) {
        $swapdata = ShiftSwap::where('id',$id)->first();
        $shiftDetails = ShiftRecord::where('id',$swapdata->shift_id)->first();
        if($shiftDetails->created_by != $user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry! you do not have permission to edit this shift'
            ], 200);
        } else {
            $swapdata->is_active = 0;
            $res = $swapdata->save();
            if($res) {
                $shiftDetails->swap_user = 0;
                $shiftDetails->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Success'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Fail'
                ], 200);
            }
        }
    }

    private function getWeekStartDate($date, $day){
		switch ($day) {
			case 'Sunday':

				$date = date_create($date);
				date_add($date,date_interval_create_from_date_string("1 days"));
			    return date_format($date,"Y-m-d");

				break;
			
			case 'Monday':
			
				return $date;

				break;

			case 'Tuesday':

				$date = date_create($date);
				date_sub($date,date_interval_create_from_date_string("1 days"));
			    return date_format($date,"Y-m-d");

				break;

			case 'Wednesday':

				$date = date_create($date);
				date_sub($date,date_interval_create_from_date_string("2 days"));
			    return date_format($date,"Y-m-d");

				break;

			case 'Thursday':

				$date = date_create($date);
				date_sub($date,date_interval_create_from_date_string("3 days"));
			    return date_format($date,"Y-m-d");

				break;

			case 'Friday':

				$date = date_create($date);
				date_sub($date,date_interval_create_from_date_string("4 days"));
			    return date_format($date,"Y-m-d");

				break;

			case 'Friday':

				$date = date_create($date);
				date_sub($date,date_interval_create_from_date_string("5 days"));
			    return date_format($date,"Y-m-d");

				break;

			case 'Saturday':

				$date = date_create($date);
				date_add($date,date_interval_create_from_date_string("2 days"));
			    return date_format($date,"Y-m-d");

				break;
		}
	}
    public function updateComment(Request $request,$id) {
        $comment = $request->comment;
        $weekdata = WeekMaster::where('id',$id)->first();
        $weekdata->comment = $comment;
        $result = $weekdata->save();
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Fail'
            ], 200);
        }
    }
    private function getWeekIdForPreview($team_id, $date) {
        $data = ShiftWeekManager::where('date_from','<=',$date)->where('date_to','>=',$date)
        ->where('team_id', $team_id)->first();
        if($data) {
            $weekid = $data->id;
        } else {
            $weekid = 0;
        }
        return $weekid;
    }
    private function getShiftOrder($day){
		switch ($day) {
			case 'Sunday':
				return 7;
				break;
			
			case 'Monday':
				return 1;
				break;

			case 'Tuesday':
				return 2;
				break;

			case 'Wednesday':
				return 3;
				break;

			case 'Thursday':
				return 4;
				break;

			case 'Friday':
				return 5;
				break;

			case 'Saturday':
				return 6;
				break;
		}
	}
    public function loadShift(Request $request,$team_id,$user_id) {
        $date = date("y-m-d");
		$day = date('l', strtotime($date));
        $weekId = ShiftWeekManager::where('date_from','<=',$date)->where('date_to','>=',$date)->where('team_id', $team_id)->first();
        if(!$weekId){
            return response()->json([
                'status' => false,
                'message' => 'Sorry! No records found'
            ], 200);
		}
        $shiftDay = WeekMaster::where('week_id', $weekId->id)->orderBy('order_by')->get();
        $data_a = [];
		$i = 0;
        foreach ($shiftDay as $row) {
			$j = 0;
			$data_a[$i]['day'] = $row['day'];
			$data_a[$i]['comment'] = $row['comment'];
			$data_a[$i]['id'] = $row['id'];
			$data_a[$i]['shift'] = ShiftRecord::with('user')->where('week_id',$weekId->id)->where('day',$row->id)->get();
			foreach ($data_a[$i]['shift'] as $value) {
                if($user_id == $value->created_by) {
                    $data_a[$i]['own_shift'] = 1; 
                } else {
                    $data_a[$i]['own_shift'] = 0;
                }
				if($value['swap_user'] != 0){
					$data_a[$i]['shift'][$j]['swap'] =ShiftSwap::where('shift_id',$value->id)->where('is_active', 1)->get();
				}else{
					$data_a[$i]['shift'][$j]['swap'] = [];
				}

				$j++;
			}
			$i++;
		}
        if($data_a){
            return response()->json([
                'data' => $data_a,
                'message' => 'Success'
            ], 200);
		}else{
            return response()->json([
                'status' => false,
                'message' => 'Sorry, no records available!'
            ], 200);
		}
    }
}
