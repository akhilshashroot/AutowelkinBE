<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\PerformanceHistory;
use App\Models\Performance;
use Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\JdSkillUpdater;
use App\Models\Requst;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    
      /**
     * show employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

		$employee_data=array();
		$employee_datas=array();
		if($request->role_id == 4)	{
			$employees = User::with('team','department','designation')->whereIn('team_id',array(111,127))->orderBy('fullname','asc')->get();
		}else{
			$employees = User::with('team','department','designation')->where('team_id','!=',42)->orderBy('fullname','asc')->get();
		}
		foreach($employees as $employee) {
			$employee_data['id'] = $employee->id;
			$employee_data['emp_id'] = $employee->emp_id;
			$employee_data['date_of_join'] =$this->getLeaveResetDate($employee->date_of_join);
			$employee_data['email'] = $employee->email;
			$employee_data['phone'] = $employee->phone;
			$employee_data['fullname'] = $employee->fullname;
			$employee_data['dob'] =($employee->dob)?User::getDobConversion($employee->dob):"";
			$employee_data['gender'] = $employee->gender;
			$ext=($employee->img_file)?$employee->img_file:$employee->emp_id.'.jpg';
			$path = public_path('storage/picture/'.$ext);
    
			if(file_exists($path)){
				$employee_data['img'] =env('APP_URL').'storage/picture/'.$ext;
			} else {
				$employee_data['img'] =env('APP_URL').'storage/picture/avatar.png';
			}
			if(isset($employee->designation)) {
				$employee_data['designation'] = $employee->designation->designation;
			} else {
				$employee_data['designation'] = "";
			}
			if(isset($employee->team)) {
				$employee_data['team'] = $employee->team->name;
			} else {
				$employee_data['team'] = "";
			}
			if(isset($employee->department)) {
				$employee_data['department'] = $employee->department->dep_name;
			} else {
				$employee_data['department'] = "";
			}
			$employee_data['cert_list'] = $employee->cert_list;
			$employee_data['buddy_assigned'] =$employee->buddy_assigned;
			$employee_data['wfh'] = $employee->no_wfh;
			$employee_data['core_status'] = $employee->core;
			$employee_data['notice_period'] = $employee->notice_period;

			$wfh_total = AttendanceLog::where('user_id', $employee->id)->where('work_loc', 2)->where('punchin','>=', $employee->date_of_join)->count();

			$employee_data['LOP'] = Requst::where('user_id',$employee->id)->where('lv_type',4)->where('lv_status',1)->sum('lv_no');
			$employee_data['WFH'] = $wfh_total + Requst::where('user_id',$employee->id)->where('lv_type',3)->where('lv_status',1)
			->where('lv_date','>=',$employee_data['date_of_join'])->where('lv_date','<=',1547445316)->sum('lv_no');
			$employee_data['SWAP'] = Requst::where('user_id',$employee->id)->where('lv_type',5)->where('lv_status',1)->sum('lv_no');
			array_push( $employee_datas, $employee_data);
		}
		return response()->json([
            'data' => $employee_datas,
            'message' => 'Success'
        ], 200);
        // $sort  =  'asc';
		// $field =  'id';

		// if(auth()->user()->role == 4)	{
		// 	$data = $this->Admin_model->getEachDepEmployees($sort,$field);	//1 == software department
		// }else{
		// 	$data = $this->getEmployees($sort,$field);	
		// }	
		
		// foreach($data as $employee){
		// 	$date_of_join=11;
		// 	$user_id=$employee->id;
		// 	$wfh=11;
		// 	$data['WFH']				= 11;
		// 	$data['LOP']			 	=11;
		// 	$data['SWAP']				=11;
		// 	$tickets						= 11;
		// 	$data['handled']			= $tickets['handled'];
		// 	$data['resolved']			= $tickets['resolved'];
		// 	$data['pending']			= $tickets['pending'];
		// 	$data['sla']				= $tickets['sla'];
		// 	$data['mandatory_hours'] 	=11;
		// 	$extra_hours_result 			=11;
		// 	if($extra_hours_result){
		// 		$data['extra_hours']	=11;
		// 	}else{
		// 		$data['extra_hours']	= 11;
		// 	}

		// }
		
	// 	$employee_data=array();
	// 	$employee_datas=array();
    //      $data = DB::table('users')
    //     ->select('users.*','department.dep_name as dept_name','team.name as team_name','designation.designation as designation'
    // //     //,DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=4 AND lv_status=1 ) as LOP')
    // //     //  ,DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=3 AND lv_status=1)  as WFH') ,
    // //     //  DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=5 AND lv_status=1)  as SWAP')
    //  )  ->leftJoin('team','users.team_id','=','team.team_id')
    //     ->leftJoin('department','users.dep_id','=','department.dep_id')
    //     ->leftJoin('designation','users.desgn_id','=','designation.desg_id')
    //     // ->leftJoin( 'performance', 'users.id', '=',
    //     //   ( DB::raw('(select max(performance.performance_id) from performance where performance.user_id=users.id)')
    //     //     ))
    //         // ,DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=4 AND lv_status=1 ) as LOP')
    //         // ,DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=3 AND lv_status=1)  as WFH') ,
    //         // DB::raw('(select sum(lv_no) from request WHERE user_id=users.id AND lv_type=5 AND lv_status=1)  as SWAP'))
          
	// 	//echo json_encode( $data, JSON_PRETTY_PRINT);
    //     ->orderBy('users.id', 'asc')
    //     ->groupby('users.id')
    //     ->get();
    //    	// foreach($data as $employee){
	// 	// 	$date_of_join=$this->getLeaveResetDate($employee->date_of_join);
    //     //    // dd($date_of_join);
	// 	// 	$user_id=$employee->id;
	// 	// 	$wfh=11;
	// 	// 	$data['WFH']				= 11;
	// 	// 	$data['LOP']			 	=11;
	// 	// 	$data['SWAP']				=11;
	// 	// 	$tickets						= 11;
	// 	// 	$data['handled']			= $tickets['handled'];
	// 	// 	$data['resolved']			= $tickets['resolved'];
	// 	// 	$data['pending']			= $tickets['pending'];
	// 	// 	$data['sla']				= $tickets['sla'];
	// 	// 	$data['mandatory_hours'] 	=11;
    //     //     $data['date_join'] 	=$date_of_join;

	// 	// 	$extra_hours_result 			=11;
	// 	// 	if($extra_hours_result){
	// 	// 		$data['extra_hours']	=11;
	// 	// 	}else{
	// 	// 		$data['extra_hours']	= 11;
	// 	// 	}
    //     // }    
	// 	// for ($i = 0; $i < count($data); $i++) {
	// 	// 	echo $array[$i]['filename'];
	// 	// 	echo $array[$i]['filepath'];
	// 	// }
	// 	foreach($data as $item) {
	// 		 $employee_data['id']=$item->id;
	// 		 $employee_data['emp_id']=$item->emp_id;
	// 		 $employee_data['name']=$item->fullname;
	// 		 $employee_data['gender']=$item->gender;
	// 		 $employee_data['phone']=$item->phone;
	// 		 $employee_data['cert_list']=$item->cert_list;
	// 		 $employee_data['core']=$item->core;
	// 		 $employee_data['buddy_assigned']=$item->buddy_assigned;
	// 		 $employee_data['notice_period']=$item->notice_period;
	// 		 $employee_data['dob']=User::getDobConversion($item->dob);
	// 		 $employee_data['dept_name']=$item->dept_name;
	// 		 $employee_data['team_name']=$item->team_name;
	// 		 $employee_data['designation']=$item->designation;
	// 		 $employee_data['date_of_join']=$this->getLeaveResetDate($item->date_of_join);
	// 	     $wfh=User::NoofWFH($item->id,$item->date_of_join);
	// 		 $wfhs=$wfh->toArray();
	// 	     $employee_data['WFH']				= $wfhs[0]['total'] + User::NoofWFHfromRequest($item->id,$item->date_of_join)->total;
	// 	     $employee_data['LOP']			 	= User::NoofLOP($item->id,$item->date_of_join)->total;	

	// 		 $employee_data['SWAP']				= User::NoofSWAP($item->id,$item->date_of_join)->total;
	// 	     $tickets						    = User::getTicketsCount($item->id);
	// 		 $employee_data['handled']			= $tickets['handled'];
	// 		 $employee_data['resolved']			= $tickets['resolved'];
	// 		 $employee_data['pending']			= $tickets['pending'];
	//          $employee_data['sla']				= $tickets['sla'];
	//          $employee_data['mandatory_hours'] 	=User::GetRealTime(User::getExtraHours($item->id)['pending_hrs']); 
	//          $extra_hours_result 			    = User::getExtraHours($item->id);
	// 	       if($extra_hours_result){
	//  	       $employee_data['extra_hours']	=User::GetRealTime(User::getExtraHours($item->id)['extra_hrs']);
	// 	        }else{
	// 	       $employee_data['extra_hours']	=User::GetRealTime(0);
	//             }
	// 		$employee_data['PE']=$item->id;
	// 		$employee_data['IE']=$item->id;
	// 		$employee_data['CE']=$item->id;
	// 	// 	//  $employee_data['id']=$item->id;
	// 	array_push( $employee_datas, $employee_data);
	// 		// to know what's in $item
	// 	}
//   foreach($data as $employee){
// 	  $employee_data=$employee->id;
//   }
// dd( $employee_data);
		return response()->json([
            'data' => $employee_datas,
            'message' => 'Success'
        ], 200);
    }

    
	function getLeaveResetDate($date_of_join){
	
		$year_previous			  =	 (int)$date_of_join;

		$fromDate=Carbon::parse($year_previous)->format('M d, Y');
		$fromDate =date('Y-m-d', strtotime($fromDate. ' + 1 days'));
		return $fromDate;

	}
	
	 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		$request->validate([
			'email' => 'required|email',
			'empid' => 'required',
			'fullname' => 'required',
			'gender' => 'required',
			//'dob' => 'required',
			'date_of_join' => 'required',
			'password' => 'required',
			'dep_id' => 'required',
			'team_id' => 'required',
			'desgn_id' => 'required',
		//	'phone' => 'required',

		],
		[
			'date_of_join.required' => 'Date of join is required',
		]);
      
        $email=User::mail_exists($request->email);
		$empid=User::empid_exists($request->empid);
		if($email == TRUE){
			return response()->json([
                'status' => false,
                'error' => 'Email id already exist'
            ], 400);

		}

		if($empid == TRUE){
     		return response()->json([
                'status' => false,
                'error' => 'EMP id already exist'
            ], 400);

		}
		$user = new User;
		$user->fullname = $request->fullname;
		$user->emp_id = $request->empid;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->phone = $request->phone;		 
		$user->gender =  $request->gender;			 
		$user->dep_id = $request->dep_id;		 
		$user->team_id = $request->team_id;
		$user->desgn_id = $request->desgn_id;
		$user->date_of_join =  strtotime($request->date_of_join);			 
		$user->dob = strtotime( $request->dob);
		$user->cert_list =  isset($request->cert_list)?$request->cert_list:"NIL";
		$user->buddy_assigned= $request->buddy_assigned;
		$user->no_wfh= $request->wfh;
		$user->core= $request->core_status;
		$user->role= 0;
		$user->notice_period= $request->notice_period;
        $result = $user->save();
		$pe=array(
			'user_id'=>$user->id,
			'date' => strtotime("now"),'preview' => 0,'creview' => 0,'tquality' => 0,'cquality' => 0,'treplies' => 0,'pviolation' => 0,'slaviolation' => 0,'wreport' => 0,
			'skypeactivity' => 0,'warning' => 0,'suspension' => 0,'blogpost' => 0,'seminars' => 0,'training' => 0,'codeof' => 0,
			'linkedin' => 0,'fb' => 0,'twitter' => 0,'insta' => 0,'comments' => 0,'ssmedia' => 0,'awards' => 0,
			'goldenresponse' => 0,'certifications' => 0,'trialpperf' => 0,'trialpperf' => 0,
			'cypviolation' => 0,'ChallengeOfTheDay'=> 0,'extracurricular'=> 0,'servicecancellation'=> 0,'interviews' => 0
		);		
		DB::table('performance')->insert($pe);	
		$data1['user_id']= $user->id;
		$data1['at_month']=date('mY');
		$daysofthemonth = date('j',strtotime('last day of this month'));

		for($i=1;$i<=$daysofthemonth;$i++){	 				

				$at_timing[$i]=array();									

		}

		$data1['at_timing']=serialize($at_timing); 
        $res=DB::table('attendance')->insert($data1);

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
		 * Show the form for editing the specified resource.
		 *
		 * @param  int  $id
		 * @return \Illuminate\Http\Response
		 */
	public function edit($id)
	{
		$user = User::find($id);
		if(!$user){
			return response()->json([
				'status' => false,
				'message' => 'Error'
			], 200);
		}
		return response()->json([
			'data' => $user,
			'message' => 'Success'
		], 200);

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
		
		$user = User::find($id);
		if(!$user ){
			return response()->json([
                'status' => false,
                'error' => 'Error'
            ], 400);
		}
                 
		if($request->fullname){

			$user->fullname = $request->fullname;

		}				

		
		if( $request->empid){

			if($request->empid ==$user->emp_id){
				$user->emp_id = $request->empid;

				
			} else{
				$empid=User::empid_exists($request->empid);
				if($empid == TRUE){

				return response()->json([
					'status' => false,
					'error' => 'EMP id already exist'
				], 400);
			  }
			  $user->emp_id = $request->empid;

			}

		}								

		if( $request->email){

			if($request->email ==$user->email){
				$user->email =	$request->email;

				
			} else{
				$email=User::mail_exists($request->email);
				if($email == TRUE){

				return response()->json([
					'status' => false,
					'error' => 'Email id already exist'
				], 400);
			  }
			  $user->email =	$request->email;

			}

		}												

		if($request->password){

			$user->password = Hash::make($request->password);

		}																

		if( $request->phone){

			$user->phone = $request->phone;		 

		}																

		if( $request->gender){

			$user->gender =  $request->gender;			 

		}																

		if($request->dep_id){

			$user->dep_id = $request->dep_id;		 

		}																

		if( $request->team_id){

			$user->team_id = $request->team_id;

		}	

		if( $request->desgn_id){

			$user->desgn_id = $request->desgn_id;

		}

		if( $request->date_of_join){

			$user->date_of_join =  strtotime($request->date_of_join);			 

		}																				

		if( $request->dob){

			$user->dob = strtotime( $request->dob);

		}																				

		if($request->buddy_assigned){

			$user->buddy_assigned= $request->buddy_assigned;

		}

		if($request->cert_list){

			$user->cert_list = $request->cert_list;

		}
		if($request->wfh==0){

			$user->no_wfh= $request->wfh;

		}elseif($request->wfh==1){
			$user->no_wfh= $request->wfh;

		}
		if($request->core_status==0){

			$user->core= $request->core_status;

		}elseif($request->core_status==1){
			$user->core= $request->core_status;

		}
		if( $request->notice_period==0){

			$user->notice_period= $request->notice_period;

		}elseif($request->notice_period==1){
			$user->notice_period= $request->notice_period;

		}
		if( $request->date_of_exit){

			$user->date_of_exit= $request->date_of_exit;

		}

        $result = $user->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
		$user=User::find($id);
		if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
		$user->delete();
		Performance::where('user_id',$id)->delete();
		return response()->json([
			'status' => true,
			'message' => 'Success'
		], 200);
	}
	
	public function getEmployeeSkillSet(Request $request) {
		$user_id = $request->user_id;
	
		$data['added'] = JdSkillUpdater::where('skill_update_status',0)->where('skill_verify_status',0)
		->where('user_id',$user_id)->orderBy('skill_id','desc')->get();
		$data['review'] = JdSkillUpdater::where('skill_update_status',true)->where('skill_verify_status',false)
		->where('user_id',$user_id)->orderBy('skill_id','desc')->get();
		$data['completed'] = JdSkillUpdater::where('skill_update_status',true)->where('skill_verify_status',true)
		->where('user_id',$user_id)->orderBy('skill_id','desc')->get();
		return response()->json([
			'data' => $data,
			'message' => 'Success'
		], 200);
	}

	public function addNewSkill(Request $request) {
		$validated = $request->validate([
            'user_id' => 'required',
			'skillname' => 'required'
        ]);
		$skill = new JdSkillUpdater();
		$skill->user_id = $validated['user_id'];
		$skill->skill_name = $validated['skillname'];
		$skill->skill_update_status = 0;
		$skill->skill_verify_status = 0;
		$result = $skill->save();
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

	public function removeSkill($id) {
		$result = JdSkillUpdater::where('skill_id',$id)->delete();
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

	public function changeSkillStatus(Request $request){
		$skill_id                   = $request->skill_id;

		$skill_update_status = $request->skill_update_status;
		$skill_verify_status   =  $request->skill_verify_status;
		$JdSkillUpdater = JdSkillUpdater::find($skill_id);
		$JdSkillUpdater->skill_update_status=$skill_update_status;
		$JdSkillUpdater->skill_verify_status=$skill_verify_status;
		$JdSkillUpdater->save();
		if($JdSkillUpdater ){
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
     * show resigned employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function showResigned(Request $request)
    {

		$employee_data=array();
		$employee_datas=array();
		$employees = User::with('team','department','designation')->where('team_id',42)->orderBy('fullname','asc')->get();
	
	//	$employees = User::with('team','department','designation')->where('team_id','!=',42)->orderBy('fullname','asc')->get();
		foreach($employees as $employee) {
			$employee_data['id'] = $employee->id;
			$employee_data['emp_id'] = $employee->emp_id;
			$employee_data['date_of_join'] =$this->getLeaveResetDate($employee->date_of_join);
			$employee_data['email'] = $employee->email;
			$employee_data['phone'] = $employee->phone;
			$employee_data['fullname'] = $employee->fullname;
			$employee_data['dob'] =User::getDobConversion($employee->dob);
			$employee_data['gender'] = $employee->gender;
			$employee_data['date_of_exit'] =($employee->date_of_exit)?date('Y-m-d', strtotime($employee->date_of_exit)):'';
			
			$ext=($employee->img_file)?$employee->img_file:$employee->emp_id.'.jpg';
			$path = public_path('storage/picture/'.$ext);
    
			if(file_exists($path)){
				$employee_data['img'] =env('APP_URL').'storage/picture/'.$ext;
			} else {
				$employee_data['img'] =env('APP_URL').'storage/picture/avatar.png';
			}
			if(isset($employee->designation)) {
				$employee_data['designation'] = $employee->designation->designation;
			} else {
				$employee_data['designation'] = "";
			}
			if(isset($employee->team)) {
				$employee_data['team'] = $employee->team->name;
			} else {
				$employee_data['team'] = "";
			}
			if(isset($employee->department)) {
				$employee_data['department'] = $employee->department->dep_name;
			} else {
				$employee_data['department'] = "";
			}
			$employee_data['LOP'] = Requst::where('user_id',$employee->id)->where('lv_type',4)->where('lv_status',1)->sum('lv_no');
			$employee_data['WFH'] = Requst::where('user_id',$employee->id)->where('lv_type',3)->where('lv_status',1)->sum('lv_no');
			$employee_data['SWAP'] = Requst::where('user_id',$employee->id)->where('lv_type',5)->where('lv_status',1)->sum('lv_no');
			array_push( $employee_datas, $employee_data);
		}
		return response()->json([
            'data' => $employee_datas,
            'message' => 'Success'
        ], 200);

	}
	 /**
     * Update  notice period.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateNoticePeriod(Request $request, $id)
    {
      
        $users = User::find($id);
        if(!$users){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $users->notice_period = $request->notice_period;
        $users->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
       
    }

 /**
     * Update  Core Employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCoreEmployee(Request $request, $id)
    {
      
        $users = User::find($id);
        if(!$users){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $users->core = $request->core_status;
        $users->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
       
    }

	/**
     * manage Wfh of Employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manageWfh(Request $request, $id)
    {
      
        $users = User::find($id);
        if(!$users){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $users->no_wfh = $request->wfh;
        $users->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
       
    }

	/**
     * manage image upload of  Employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manageUpload(Request $request, $id)
    {
      
        $users = User::find($id);
        if(!$users){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
		$validated = $request->validate([
			'image' => 'mimes:jpeg,jpg,png' // max 10000kb
  
        ]);
		$ext=($users->img_file)?$users->img_file:$users->emp_id.'.jpg';
			$path = public_path('storage/picture/'.$ext);

			if(file_exists($path)){
				Storage::delete('public/picture/'.$ext);
			} 


        if($request->hasFile('image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
			
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $users->emp_id.'_'.$users->id.'.'.$extension;
            // Upload Image
			$path = public_path('storage/picture/'.$fileNameToStore);

			if(file_exists($path)){
				Storage::delete('public/picture/'.$fileNameToStore);
			} 
            $path = $request->file('image')->storeAs('public/picture',$fileNameToStore);

            $users->img_file = $fileNameToStore ;
        } 
          
        $result = $users->save();
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
