<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requst;
use App\Mail\LeaveRequestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use DB;
class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id= $id;
		$user_data					  =User::find($user_id);
		$date_of_join				 = $user_data->date_of_join;
		$new_joining_date 		  = User::getLeaveResetDate($date_of_join);
		$requests=Requst::where('user_id',$user_id)->where('lv_date','>=',$new_joining_date)->orderby('lv_id', 'desc')->get();	
		$outputs=array();
		foreach($requests as $request){
			$rqtype=$this->RequestType($request['lv_type']);
			if($request['lv_status']==0){
				$status="Pending";
			}elseif($request['lv_status']==1){
				$status="Approved";
			}else{
				$status="Rejected";
			}

			if(!empty($request['lv_date_to']) && $request['lv_date_to']!=$request['lv_date'] ){
                $output['leave_date']=date('d M Y',$request['lv_date']).' to '.date('d M Y',$request['lv_date_to']);
                // $output['to_date']=date('d M Y',$request['lv_date_to']);
			}else{
                $output['leave_date']=date('d M Y',$request['lv_date']);

			}
            $output['type']=$rqtype;
			if($status == "Approved"){
                $output['status']=$status;

            }elseif($status == "Rejected"){
                $output['status']=$status;

            }else{
                $output['status']=$status;

            	}

                $output['approvedby']=   $request['approvedby'];
                $output['lv_purpose']= $request['lv_purpose'];
                array_push($outputs,$output);	
		}	
        //   $check['dept_id']=$user_data->dep_id;
        //    if($user_data->designation){
        //     $check['designation']=$user_data->designation->designation;

        //    }else{
        //     $check['designation']="";
        //    }
        //    $check['notice_period']=$user_data->notice_period ;
        //    $getLeaveResetDate		=User::getLeaveResetDate($user_data->date_of_join);

        //    $check['holiday_total'] = (Requst::NoHolidaysLeave( $user_data->id, $getLeaveResetDate)[0]['total'])?Requst::NoHolidaysLeave( $user_data->id, $getLeaveResetDate)[0]['total']:0;
        //    $check['casual_total']	 =(Requst::Noofcasualleaves($user_data->id,$getLeaveResetDate)[0]['total'])?Requst::Noofcasualleaves($user_data->id,$getLeaveResetDate)[0]['total']:0;
        //    $check['sick_total'] =	(Requst::Noofsickleaves($user_data->id,$getLeaveResetDate)[0]['total'])?Requst::Noofsickleaves($user_data->id,$getLeaveResetDate)[0]['total']:0;
        //    $check['core'] =	$user_data ->core;
        //    array_push($outputs,$check);	

        return response()->json([
            'data' => $outputs,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Show the request type dropdown.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestDropdown($id)
    { 
        $outputs=array();
        $user_data					  =User::find($id);
		$date_of_join				 = $user_data->date_of_join;
		$new_joining_date 		  = User::getLeaveResetDate($date_of_join);
        $check['dept_id']=$user_data->dep_id;
        if($user_data->designation){
         $check['designation']=$user_data->designation->designation;

        }else{
         $check['designation']="";
        }
        $check['notice_period']=$user_data->notice_period ;
        $getLeaveResetDate		=User::getLeaveResetDate($user_data->date_of_join);

        $check['holiday_total'] = (Requst::NoHolidaysLeave( $user_data->id, $getLeaveResetDate)[0]['total'])?Requst::NoHolidaysLeave( $user_data->id, $getLeaveResetDate)[0]['total']:0;
        $check['casual_total']	 =(Requst::Noofcasualleaves($user_data->id,$getLeaveResetDate)[0]['total'])?Requst::Noofcasualleaves($user_data->id,$getLeaveResetDate)[0]['total']:0;
        $check['sick_total'] =	(Requst::Noofsickleaves($user_data->id,$getLeaveResetDate)[0]['total'])?Requst::Noofsickleaves($user_data->id,$getLeaveResetDate)[0]['total']:0;
        $check['core'] =	$user_data->core;

        if ( $check['notice_period'] != 1) {
        $request_type['name']="Swap Shift";
        $request_type['id']=5;
        array_push($outputs,$request_type);	

        }
        if (( $check['designation']  != 'L1 Server Engineer') && ( $check['designation'] != 'Trainee')){
        $request_type['name']="Work From Home";
        $request_type['id']=3;
        array_push($outputs,$request_type);	

        }
        if($check['casual_total']<6 && $check['notice_period'] != 1){
            $request_type['name']="Casual Leaves";
            $request_type['id']=1;
            array_push($outputs,$request_type);	

        }
        if( $check['sick_total'] <6){ 
            $request_type['name']="Medical Leaves";
            $request_type['id']=2;
            array_push($outputs,$request_type);	

        }
        $request_type['name']="Loss of Pay";
        $request_type['id']=4;
        array_push($outputs,$request_type);	

        if((( $check['core'] == 1) || (($check['dept_id'] != 2) && ($check['dept_id'] != 22) && ($check['dept_id'] != 46) && ($check['dept_id'] != 51))) && (  $check['holiday_total'] < 10)){ 
        $request_type['name']="Holidays";
        $request_type['id']=7;
        array_push($outputs,$request_type);	

        }

        return response()->json([
            'data' => $outputs,
            'message' => 'Success'
        ], 200);
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
            'user_id' => 'required',
            'request_type' => 'required',
            'reason' => 'required',
            'approved_by' => 'required',
            'request_days' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',

        ]);
        $user_id           		 = $request->user_id;
        $user_data				 =  User::find($user_id);
		$date_of_join			 = $user_data->date_of_join;
        $request['user_id']		 = $user_id;
        $request['lv_type']		 = $request->request_type;		
        $request['lv_purpose']	 = $request->reason;
        $request['approvedby']   = $request->approved_by;
        $request['lv_no'] 		 = $request->request_days;
        $date					 = strtotime($request->from_date);
        $dateto					 = strtotime($request->to_date);
        $request['lv_aply_date'] =strtotime('now');
        $request['lv_date']		 =$date;
        $request['lv_date_to']	 =$dateto;

    $today = strtotime('today');
	
    //Open Limiting CL upto 6
    
    //if admin skip date  applied_as_admin
    if( $request->applied_as_admin==0){
        if($date<$today ){
            return response()->json([
                'status' => false,
                'error' => 'Wrong from date !'
            ], 400);    
    }		
    if( $dateto < $today){
        return response()->json([
            'status' => false,
            'error' => 'Wrong to date !'
        ], 400);    
      }	
        $today = strtotime('today');
        if(empty($date	) || ($date<$today) ){
            return response()->json([
                'status' => false,
                'error' => 'Wrong to date !'
            ], 400);    
        }		
    
        if($dateto < $today){
            return response()->json([
                'status' => false,
                'error' => 'Wrong to date !'
            ], 400);    
        }}
        $request['is_admin']=0;
        if($request->applied_as_admin==1 && $request['lv_type']	== 4){
            $request['is_admin']		=	1;
        }

        if($request['lv_type']     == 1){
            $new_joining_date = User::getLeaveResetDate($date_of_join);
            $no_cl                  = Requst::Noofcasualleaves($user_id,$new_joining_date);
            if($no_cl[0]['total']>0){ 
                $applied_no_cl      = $request['lv_no'];
                $prev_tot_cl        = $no_cl[0]['total'];
                $sum_cls            = $applied_no_cl + $prev_tot_cl;
                $CL_left            = 6 - $prev_tot_cl ; 
                if($sum_cls>6){
                    return response()->json([
                        'status' => false,
                        'error' =>  $CL_left.' Casual Leaves left.'
                    ], 400);  
                }
            }
        
//rewrite code --renjith for //leave ML
        if($request['lv_type'] == 2){
            $new_joining_date =  User::getLeaveResetDate($date_of_join);
            $no_cl                   = Requst::Noofsickleaves($user_id,$new_joining_date);
            if($no_cl[0]['total']>0){
                $applied_no_cl      = $request['lv_no'];
                $prev_tot_cl        = $no_cl[0]['total'];
                $sum_cls            = $applied_no_cl + $prev_tot_cl;
                $CL_left            = 6 - $prev_tot_cl ; 
                if($sum_cls>6){

                    return response()->json([
                        'status' => false,
                        'error' =>  $CL_left.' Medical Leaves left.'
                    ], 400);  
                }
            }
        }

        if($request['lv_type'] == 7){
            $new_joining_date =  User::getLeaveResetDate($date_of_join);
            $no_holidays                   = Requst::NoHolidaysLeave($user_id,$new_joining_date);
            if($no_holidays[0]['total']>0){
                $applied_no_holiday      = $request['lv_no'];
                $prev_tot_holiday        = $no_holidays[0]['total'];
                $sum_holidays            = $applied_no_holiday + $prev_tot_holiday;
                $HOLIDAYS_left            = 10 - $prev_tot_holiday ; 
                if($sum_holidays>10){
                    return response()->json([
                        'status' => false,
                        'error' =>  $HOLIDAYS_left.' holidays left.'
                    ], 400);  
                }
            }
        }
    }
    $request['lv_img']='';
    if($request->hasFile('userfile')){
        // Get filename with the extension
        $filenameWithExt = $request->file('userfile')->getClientOriginalName();
        //Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        
        // Get just ext
        $extension = $request->file('userfile')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $user_id.'_'.strtotime('now').'.'.$extension;
        // Upload Image
        $path = $request->file('userfile')->storeAs('public/leavefiles',$fileNameToStore);

        $request['lv_img']=$fileNameToStore ;
    } 
      
    if($request['lv_type']==5){
        $request['lv_status']=1;
    }
    else{
        $request['lv_status']=0;
    }

    $rqst = new Requst;
    $rqst->user_id =  $request['user_id']	;
    $rqst->lv_type =   $request['lv_type']	;
    $rqst->lv_purpose =  $request['lv_purpose'];
    $rqst->approvedby =  $request['approvedby'] ;
    $rqst->lv_no =   $request['lv_no'] 	;
    $rqst->lv_aply_date =  $request['lv_aply_date'];
    $rqst->lv_date =  $request['lv_date']	;
    $rqst->lv_date_to = $request['lv_date_to'];
    $rqst->lv_img =  $request['lv_img'];
    $rqst->is_admin = $request['is_admin'];
    $rqst->lv_status =  $request['lv_status'];
    $result = $rqst->save();
    $email4 = "requests@hashroot.com";//to
    
  
    // $insert_a =  json_decode( json_encode($request), true);

    // $result=DB::table('request')->insert($insert_a);
    if($result) {
        try {

        $test= Mail::send(new LeaveRequestMail( $user_data, $request,$this->RequestType($request['lv_type'])));
         Log::debug("Leave Request".$test);
   
        } catch (\Exception $e) {
            Log::debug("Leave Request".$e->getMessage());

         $e->getMessage();
        }
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
    // public function show($id)
    // {
    //     //
    // }

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
       
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        
    }
    function RequestType($type){
		switch($type){
			case 1:$text="CL";
			break;
			case 2:$text="ML";
			break;
			case 3:$text="WFH";
			break;
			case 4:$text="LOP";
			break;			
			case 5:$text="SWAP";
			break;
			case 6:$text="Restricted WFH";
			break;
			case 7:$text="Holiday";
			break;
		}
		return $text;
	}
}

