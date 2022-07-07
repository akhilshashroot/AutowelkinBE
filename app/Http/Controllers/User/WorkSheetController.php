<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JdSkillUpdater;
use App\Models\WorkReport;
use App\Models\DailyActivity;
use App\Models\AttendanceLog;
use App\Models\TicketDetails;
use App\Models\WeeklyActivity;
use App\Models\WeeklyData;
use App\Models\MonthlyActivity;
use App\Models\MonthlyData;
use App\Models\RepeatMonthlyData;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SkillUpdate;
use Illuminate\Support\Facades\Log;
class WorkSheetController extends Controller
{


	public	$ticket_details_flag = true;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$user_id )
    {
       // $user_id   = $request->user_id;
		$details    = User::Leftjoin('department', 'users.dep_id','=','department.dep_id')
                            ->where('users.id',$user_id)->get()->toArray(); 
                         
		$dep_id    = $details[0]['dep_id']; 
        //if ServerAdmins, then fetch tickets count 
		$work_data=array();

//#######################daily Activity starts##################################

		$dat         =array();
		$dat['daily_act']=array();
		$dat['daily_act_ticket']=array();
		$dat1         =array(); 
		$dat2         =array();
		$dat['daily_act_hr']=array();
		$dat['daily_act_hr_task']=array();
		$statusfield =  0;	 	 
		//$daily_act_id_list = '';      // old code
		$daily_act_id_list = array();   // Updated by Vivek on 16-07-2021
		$new_monthly_dat   = DailyActivity::get_ids_daily($user_id,$dep_id);
		foreach($new_monthly_dat as $new_mmarray){
			//$daily_act_id_list[] = $new_mmarray['daily_act_id'];   //old code
			$daily_act_id_list = $new_mmarray['daily_act_id'];       // Updated by Vivek on 16-07-2021
		}
		$datas =DailyActivity::get_Daily_Acts($user_id,$dep_id);
		
		if(count($datas)>0){ 
			$att_det		       = AttendanceLog::Get_All_att_log($user_id);

		if((!empty($att_det[0]['punchin'])) && (empty($att_det[0]['punchout']))){ 	
			$att_id   			   = $att_det[0]['att_id'];
			if((count($att_det)>0) && !empty($att_det[0]['work_report'])){
				$unser_work_report = unserialize($att_det[0]['work_report']);
			}else{ 
				$i=0;
				foreach($datas as $daily_act){
					$act_data[$daily_act['daily_act_id']]['field_type']  = $daily_act['field_type'] ;
					$act_data[$daily_act['daily_act_id']]['activity']    = $daily_act['daily_act'] ;
					$act_data[$daily_act['daily_act_id']]['status']      = 0 ;
					$act_data[$daily_act['daily_act_id']]['reply']       = '';
					$data['work_report']        						 = serialize($act_data);
					User::Insert_wrk_rpt($data,$user_id,$att_id);
					$i++;
				}
			} 
			$unser = unserialize($att_det[0]['work_report']);

			//$this->dd($datas);
			//dd($datas);
			 foreach($datas as $daily_acts){
				if($daily_acts['field_type']==0 ){
				//	$dat['daily_act']=	$daily_acts['daily_act'];
					if(!isset($unser[$daily_acts['daily_act_id']]['reply']) || $unser[$daily_acts['daily_act_id']]['reply']!=1){ 
						$dat3['daily_act_id']= $daily_acts['daily_act_id'];
					// 	$dat['daily_act_id'] =$daily_acts['daily_act_id'];
					 $dat3['daily_act'] =$daily_acts['daily_act'];
	         		$dat3['reply'] =isset($unser[$daily_acts['daily_act_id']]['reply'])?$unser[$daily_acts['daily_act_id']]['reply']:"";

					 	array_push($dat['daily_act_hr_task'],$dat3);
					}else{	
						$dat3['daily_act'] =$daily_acts['daily_act'];

						$dat3['daily_act_id']= $daily_acts['daily_act_id'];
						$dat3['reply'] =isset($unser[$daily_acts['daily_act_id']]['reply'])?$unser[$daily_acts['daily_act_id']]['reply']:"";
						array_push($dat['daily_act_hr_task'],$dat3);

						
									}
						//	$dat .='</div>';
				}elseif($daily_acts['field_type']==1){
					if($this->ticket_details_flag == true){
						$this->ticket_details_flag = false;
					//	$dat['ticket_details'] =$this->submited_ticket_details($user_id);

					}
					//$dat['daily_act']=	$daily_acts['daily_act'];
				//	dd($unser[$daily_acts['daily_act_id']]['reply']);
					 if(!isset($unser[$daily_acts['daily_act_id']]['reply']) ||$unser[$daily_acts['daily_act_id']]['status']==0){ 

						$daily_acts['reply'] =isset($unser[$daily_acts['daily_act_id']]['reply'])?$unser[$daily_acts['daily_act_id']]['reply']:"";
						array_push($dat['daily_act'],$daily_acts);

				}else{	
					$daily_acts['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
					array_push($dat['daily_act'],$daily_acts);

				}
				}else{ //For text field = 2 = number
			//	$dat2['daily_act'] =$daily_acts['daily_act'];
					if(!isset($unser[$daily_acts['daily_act_id']]['reply']) || $unser[$daily_acts['daily_act_id']]['status']!=0){
						
						switch ($daily_acts['daily_act_id']) {
							case 599:
								$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
								$dat2['daily_act'] =$daily_acts['daily_act'];
								$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
								array_push($dat['daily_act_ticket'],$dat2);
								break;
								case 6942:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =isset($unser[$daily_acts['daily_act_id']]['reply'])?$unser[$daily_acts['daily_act_id']]['reply']:"";
									array_push($dat['daily_act_hr'],$dat2);
									break;
							case 691:
							case 703:
							
							case 693:
								case 601:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
									array_push($dat['daily_act_ticket'],$dat2);
									break;
							case 600:
								$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
								$dat2['daily_act'] =$daily_acts['daily_act'];
								$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
								array_push($dat['daily_act_ticket'],$dat2);
								break;
	
							case 701:
								case 710:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
									array_push($dat['daily_act_ticket'],$dat2);
									break;
							case 709:
								$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
								$dat2['daily_act'] =$daily_acts['daily_act'];
								$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
								array_push($dat['daily_act_ticket'],$dat2);
								break;
								case 711:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
									array_push($dat['daily_act_ticket'],$dat2);
									break;
	
							default:
						
								break;
						}

					}else{	
						switch ($daily_acts['daily_act_id']) {
							case 599:
								$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
								$dat2['daily_act'] =$daily_acts['daily_act'];
								$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
								array_push($dat['daily_act_ticket'],$dat2);
								break;
								case 6942:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
									array_push($dat['daily_act_hr'],$dat2);
									break;
							case 691:
							case 703:
						
								case 601:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
									array_push($dat['daily_act_ticket'],$dat2);
									break;
							case 600:
								$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
								$dat2['daily_act'] =$daily_acts['daily_act'];
								$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
								array_push($dat['daily_act_ticket'],$dat2);
								break;
							case 693:
							case 701:
							case 709:
								$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
								$dat2['daily_act'] =$daily_acts['daily_act'];
								$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
								array_push($dat['daily_act_ticket'],$dat2);
								break;
								case 710:
									$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
									$dat2['daily_act'] =$daily_acts['daily_act'];
									$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
									array_push($dat['daily_act_ticket'],$dat2);
									break;
	
									case 711:
										$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
										$dat2['daily_act'] =$daily_acts['daily_act'];
										$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
										array_push($dat['daily_act_ticket'],$dat2);
										break;
							default:
							$dat2['daily_act_id'] =$daily_acts['daily_act_id'];
							$dat2['daily_act'] =$daily_acts['daily_act'];
							$dat2['reply'] =$unser[$daily_acts['daily_act_id']]['reply'];
							array_push($dat['daily_act_ticket'],$dat2);

							break;
						}

					}





				}

	//			$dat .="Sttaatt";





			}
		//	$dat=$dat['daily_act_ticket'];
		if($dep_id ==52){
			$array =$dat['daily_act_ticket'];
			
			
			$base = $dat['daily_act_ticket'];
			$replacements = array(0 => $dat['daily_act_ticket'][0], 1 => $dat['daily_act_ticket'][1],2=>$dat['daily_act_ticket'][2]);
			$replacements2 =  array(0 => $dat['daily_act_ticket'][2], 1 => $dat['daily_act_ticket'][0],2=>$dat['daily_act_ticket'][1]);
			$basket = array_replace($base, $replacements, $replacements2);

			$dat['daily_act_ticket']= $basket ;
		}
			$work_data['work_activity']=$dat;
			// $work_data['work_activity2']=$dat2;
			// $work_data['work_activity1']=$dat1;

			//  echo "<div  class='row mat1'>$dat</div><br/>";
		    //  echo "<div  class='row mat2'>$dat2</div><br/>";
			//  echo "<div  class='row mat3'>$dat1</div><br/>"; 
			//  if($dep_id==2){
			//  echo '<a onclick="reports_user_mod()"><button style="float:left;" id="view_all_rpt" type="button" class="btn btn-brand m-btn m-btn--icon m-btn--pill m-btn--air"  >View All Reports</button></a> <br /> <br /> ';
			// }//Details of tickets
			 if($dep_id==16  || $dep_id==18 ){
				// echo '<form id="addJD_form22" action="'. base_url("admin/change_jd").'" method="post">
				// 					<div class="modal-body" style="padding-left: 0; padding-right: 0;padding-top: 0;">
				// 						<div class="m-portlet__body" style="padding-left: 0;padding-right: 0;padding-top: 0;">
				// 							<div class="form-group ">
				// 							<div class="row">
				// 							<div class="col-md-12">
				// 							<div class="form-group m-form__group "  id="add_daily_container">
				// 									<div id="add_daily" >
				// 										<label class="form-control-label ">
				// 											 Details of tickets worked
				// 										</label>
				// 										<textarea placeholder="You can add reports for additional tasks if you`ve done any." rows="6" style="border-color: #6867dd;"  class="form-control m-input" name="daily_act" id="work_report"></textarea>											
				// 									</div>
				// 							</div>
				// 					<div class="form-group m-form__group text-right">
				// 						<button id="add_work_button" type="button" class="btn btn-brand m-btn m-btn--icon m-btn--pill m-btn--air"  onclick="add_workreport()">Save </button>
				// 					</div>
				// 						<div id="new_acct"></div>
				// 					</div>
				// 					<div class="col-md-12">
				// 						<div id="work_lists" style="max-height: 250px;overflow: auto; text-overflow: ellipsis; white-space: normal; word-break:break-all;" > 
				// 						</div>
				// 					</div>
				// 					</div>
				// 					</div>
				// 				</div>
				// 			</div>
				// 		</form>';

	}
			//Close details of tickets
			}
			else{
				// echo('<div  class="row"><div class="col-md-12"><span style="color: red;border: 1px solid #dedede;display: block;padding: 15px;">You have to punchin to view your daily reports </span> </div></div><br />
				// <a onclick="reports_user_mod()"><button style="float:left;" id="view_all_rpt" type="button" class="btn btn-brand m-btn m-btn--icon m-btn--pill m-btn--air"  >View All Reports</button></a>
				// <br /> <br /> ');
				$work_data["punchin"]="You have to punchin to view your daily reports";

			}
		}



        return response()->json([
            'data' => $work_data,
            'message' => 'Success'
        ], 200);
    }



  /**
     * Sshow ticket details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function getTicketData(Request $request,$user_id){
		$result = $this->submited_ticket_details($user_id);
		
        return response()->json([
            'data' => $result,
            'message' => 'Success'
        ], 200);
	}


  /**
     * Show ticket count.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function getTicketCount(Request $request,$user_id){
		$details    = User::Leftjoin('department', 'users.dep_id','=','department.dep_id')
		->where('users.id',$user_id)->get()->toArray(); 
	 
		$dep_id    = $details[0]['dep_id']; 
		//if ServerAdmins, then fetch tickets count 
		$work_data=array();
		if($dep_id==2 || $dep_id==46  || $dep_id==51  || $dep_id==52){ 
		$ticketCount =WorkReport::getTicketsCount($dep_id,$user_id );
		$ticketCount['handled'];
		$ticketCount['resolved'];
		$ticketCount['pending'];
		$ticketCount['sla'];
		$work_data['ticket_data']=$ticketCount;
		}

        return response()->json([
            'data' => $work_data,
            'message' => 'Success'
        ], 200);
	}



	public function submited_ticket_details($user_id){
		$current_timestamp = strtotime('now');
		$attendance_log =AttendanceLog::get_dailyStatus($user_id);
	//	dd($attendance_log);
	$message_contents=array();

	     if(	$attendance_log){
		$att_id = $attendance_log[0]['att_id'];
		$date = date('Y-m-d', $current_timestamp);
		$result = TicketDetails::get_submited_ticket_details($user_id, $att_id);
		if(!$result){
			return '';
		}
	
		foreach ($result as $key => $value) {
			$message_content['ticket_id'] = $value->id;
			$message_content['url'] = $value->ticket_id;
			$message_content['response'] = $value->response;
			$message_content['sla'] = $value->sla;
			array_push(	$message_contents,$message_content);
		}

	}
		return $message_contents;
		
	}


    /**
     * weekly report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function weeklyData(Request $request,$user_id){
		$details    = User::Leftjoin('department', 'users.dep_id','=','department.dep_id')
		->where('users.id',$user_id)->get()->toArray(); 
	 
		$dep_id    = $details[0]['dep_id']; 
		$lastsun=strtotime('last Sunday');
		$weekly_activity= WeeklyActivity::where('dep_id',$dep_id)->get();
		$weekly_acts['wa_activity']=array();

		$weekly_acts['wa_id']=array();
		$work_data['weekly_act']=array();

					if(count($weekly_activity)>0){

						foreach($weekly_activity as $week){

								$weekly_data= WeeklyData::where('wd_date','>=',$lastsun)			
													->where('weekly_id',$week['wa_id'])		
													->where('user_id',$user_id)->get();			
								$weekstatus =" ";
								$input_data='';
								if(count($weekly_data)>0){
									$weekstatus =" ";
									$input_data=$weekly_data[0]['wd_status'];
								}
									if($week['wa_field_type']==0){ //field type checkbox
									$weekly_acts['wa_activity']=$week['wa_activity'];
									$weekly_acts['wa_id']=$week['wa_id'];
									$weekly_acts['type']  ="checkbox";
									$weekly_acts['reply']  =$input_data;
									array_push($work_data['weekly_act'],$weekly_acts);
								
								}elseif($week['wa_field_type']==1){ //Field type text area
									$weekly_acts['wa_activity']=$week['wa_activity'];
									$weekly_acts['wa_id']=$week['wa_id'];
									$weekly_acts['reply']  =$input_data;
									$weekly_acts['type']  ="Textarea";
									array_push($work_data['weekly_act'],$weekly_acts);
						}else{ //Field type number
							$weekly_acts['wa_activity']=$week['wa_activity'];
							$weekly_acts['wa_id']=$week['wa_id'];

							array_push($work_data['weekly_act'],$weekly_acts);			}

						}
				

					}

		  return response()->json([
            'data' => $work_data,
            'message' => 'Success'
        ], 200);
	
	}



    /**
     * Monthly data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function MonthlyData(Request $request,$user_id){
		$user_data =  User::Leftjoin('department', 'users.dep_id','=','department.dep_id')
		->where('users.id',$user_id)->get()->toArray();              
		$dep_id    = $user_data[0]['dep_id'];             
		$monthly_act = MonthlyActivity::where('dep_id',$dep_id)->get();
		if(count($monthly_act)>0){
			$month_a = ' ';
			$work_data['montly_act']= array();
			$month_a= array();
			$weekstatus = '';
			foreach($monthly_act as $m_act){
				$date = strtotime('01-m-Y');
				$res = RepeatMonthlyData::check_monthly_datas($user_id,$m_act['ma_id'],$date);
					$M_input_data = "";

				if(count($res)>0){
					$M_input_data = $res[0]->md_status;
					if($m_act['ma_field_type']==0){	//field type Checkbox					
						$month_a['ma_activity']  =nl2br($m_act['ma_activity']);
						$month_a['ma_id']  =$m_act['ma_id'];
						$month_a['type']  ="Checkbox";
						$month_a['reply']  =$M_input_data;
						array_push($work_data['montly_act'],$month_a);	
					}elseif($m_act['ma_field_type']==1){ //Field type Text area
						$month_a['ma_activity']  =nl2br($m_act['ma_activity']);
						$month_a['ma_id']  =$m_act['ma_id'];
						$month_a['reply']  =$M_input_data;
						$month_a['type']  ="Textarea";

						array_push($work_data['montly_act'],$month_a);	
						}else{
							$month_a['ma_activity']  =nl2br($m_act['ma_activity']);
							$month_a['ma_id']  =$m_act['ma_id'];
							array_push($work_data['montly_act'],$month_a);			}
					//Close if
				}else{
						
						if($m_act['ma_field_type']==0){
							$month_a['ma_activity']  =nl2br($m_act['ma_activity']);
							$month_a['ma_id']  =$m_act['ma_id'];
							$month_a['type']  ="Checkbox";
							$month_a['reply']  ="";

							array_push($work_data['montly_act'],$month_a);	
						}elseif($m_act['ma_field_type']==1){
							$month_a['ma_activity']  =nl2br($m_act['ma_activity']);
							$month_a['ma_id']  =$m_act['ma_id'];
							$month_a['type']  ="Textarea";
							$month_a['reply']  ="";
							array_push($work_data['montly_act'],$month_a);					}
					else{
						$month_a['ma_activity']  =nl2br($m_act['ma_activity']);
						$month_a['ma_id']  =$m_act['ma_id'];
						array_push($work_data['montly_act'],$month_a);			   }
				}
			}
		}else{

			$work_data['montly_act']=array();
		}

		return response()->json([
            'data' => $work_data,
            'message' => 'Success'
        ], 200);
	
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
		$dept=User::where('id', $request->user_id)->first();
		//dd($dept);
		$daily_data_id     = $request->daily_act_id;

		if($dept->dep_id==52){
			$daily_data_id     = 711;

		}
		$att_id           	   =$request->att_id;
		$daily_input        = $request->daily_inputValue;
		if(! $request->daily_inputValue){
			$daily_input        = 1;

		}
		$user_id    = $request->user_id;
		$ticket_details_a['ticket_url'] =$request->ticket_url;
		$ticket_details_a['ticket_response'] =$request->ticket_response;
		$ticket_details_a['ticket_sla']  =$request->ticket_sla;
		if(	$ticket_details_a['ticket_url'] && 	$ticket_details_a['ticket_response'] && $ticket_details_a['ticket_sla'] ){
			$details_update_res = $this->update_ticket_details($ticket_details_a,$user_id);
			if($details_update_res == false){
				$this->jsonOutput(['status' => false, 'message' => 'Ticket details update failed']);
			}
		}
		$user_id 											   = $request->user_id;
		$daily_datas_stat  			    				   = AttendanceLog::get_dailyStatus($user_id);
		$unser_data        			  					    = unserialize($daily_datas_stat['0']['work_report']);	
		$sla_violation										 = $daily_datas_stat['0']['sla_violation'];
		$unser_data[$daily_data_id]['status']	  = 1;
		$daily_input   										  = $this->clean($daily_input);
		$unser_data[$daily_data_id]['reply'] 	   = $daily_input;
		$unser_data[$daily_data_id]['time'] 	  = strtotime('now');
		$ser_dat      										   = serialize($unser_data);	
		$result['sla']										  = 0;
		if($ticket_details_a['ticket_sla']=="30 - 35 min" || $ticket_details_a['ticket_sla']=="35 - 40 min" || $ticket_details_a['ticket_sla']=="40 - 45 min" || $ticket_details_a['ticket_sla']=="45 - 50 min" || $ticket_details_a['ticket_sla']=="50 - 55 min" || $ticket_details_a['ticket_sla']=="55 - 60 min" || $ticket_details_a['ticket_sla']=="above 1 hour"){
			$ser['sla_violation']							= $sla_violation+1;
			$result['sla']										=$ser['sla_violation'];
		}
		$ser['work_report']								  = $ser_dat; 
		$result	=AttendanceLog::where('user_id',$user_id)->where('att_status',0)->first();
		$result->work_report=$ser['work_report'];
		$result->save();
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

	public function clean($content) {
		//   $string = str_replace(' ', ' ', $content); // Replaces all spaces with hyphens.
		   $string = preg_replace('/[^A-Za-z0-9\-_#!&@.$%=+():\n]/', ' ', $content); // Removes special chars. 
		   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}


    /**
     * Store a weekly data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	Public function updateWeekly(Request $request){

		$wactvity['user_id'] = $request->user_id;
		$wactvity['weekly_id']= $request->wa_id;
		$wactvity['wd_date']=strtotime('now');
		$wactvity['wd_status']= $request->weekly_inputValue;
		$lastsun=strtotime('last Sunday');
		$w_data = WeeklyData::check_weekrow_in_data($wactvity,$lastsun);
		//dd(count($w_data));
		if(count($w_data) >0 ){
             foreach($w_data as $wdat){
				$week_id = $wdat['wd_id'];
				$result=WeeklyData::find($week_id );
				$result->wd_status =$request->weekly_inputValue;
				$result->save();		

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
		else{
		$result=new WeeklyData();
		$result->user_id  =  $request->user_id;
		$result->weekly_id = $request->wa_id;
		$result->wd_date =strtotime('now');
		$result->wd_status =$request->weekly_inputValue;
		$result->save();
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

	/**
	 * Update monthly activity datas..........
	 */
	Public function updateMonthly(Request $request){
		$input_data =  $request->monthly_inputValue;
		$mid        = $request->mid;
		$user_id        = $request->user_id;
		$res = RepeatMonthlyData::checkrow($mid,$user_id);
		if( count($res) >0 ){
			foreach($res as $result){
				$md_id = $result['md_id'];
               	$results=RepeatMonthlyData::where('md_id',$md_id)->where('user_id',$user_id )->first();
				$results->md_status =$input_data;
				$results->save();	
			}
			if($results) {
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

		}else{		
				$result=new RepeatMonthlyData();
	           	$result->user_id  = $user_id;
	           	$result->monthly_id =$mid;
	           	$result->md_date =strtotime('now');
	           	$result->md_status =$input_data;
	           	$result->save();
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
   /**
     * update ticket details
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function update_ticket_details($ticket_details_a,$user_id){
		$user_id = $user_id;
		$attendance_log = AttendanceLog::get_dailyStatus($user_id);
		$att_id = $attendance_log[0]['att_id'];
		$ticket_ins = [];
		// //dd($ticket_details_a);
		// //foreach ($ticket_details_a as $value) {
			
		// 	$insert_a = new \stdClass();
		// 	$insert_a->att_id = $att_id;
		// 	$insert_a->user_id = $user_id;
		// 	$insert_a->ticket_id = $ticket_details_a['ticket_url'];
		// 	$insert_a->response = htmlspecialchars($ticket_details_a['ticket_response']);
		// 	$insert_a->sla = $ticket_details_a['ticket_sla'];
		// 	array_push($ticket_ins, $insert_a);
		// //}
		$insert_a = new TicketDetails();
			$insert_a->att_id = $att_id;
			$insert_a->user_id = $user_id;
			$insert_a->ticket_id = $ticket_details_a['ticket_url'];
			$insert_a->response = htmlspecialchars($ticket_details_a['ticket_response']);
			$insert_a->sla = $ticket_details_a['ticket_sla'];
			$insert_a->save();
		return $insert_a;
	}



    /**
     * update ticket response
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function updateTicketResponse(Request $request){
		$ticket_id = $request->ticket_id;
		$response  = $request->response;

		$updated = TicketDetails::find($ticket_id);
		$updated->response=$response;
		$updated->save();
		if($updated) {
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
     * show skill list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function getSkillList(Request $request,$user_id){
		//$user_id =  $request->user_id;
		$skills		 = JdSkillUpdater::getSkillSets($user_id);
       if($skills->status){
          	$skill_data=array();
          	foreach ($skills->data as $skill){
          	
          		$skill['skill_name'];
          				
          		if($skill['skill_update_status']==0 && $skill['skill_verify_status']==0){
          			$skill['status']=0;
          		}
          		if($skill['skill_update_status']==1 && $skill['skill_verify_status']==0){
          			$skill['status']=1;
          
          		}
          		if($skill['skill_update_status']==1 && $skill['skill_verify_status']==1){
          			$skill['status']=1;
          
          		}
          	//	$work_data['skill data']=$skill;
                array_push(	$skill_data,$skill);
          	}
		}
          
		return response()->json([
            'data' => $skill_data,
            'message' => 'Success'
        ], 200);
	} 



	/**
	* update skill status
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function skillStatusUpdater(Request $request){
		$skill_id =  $request->skill_id;
		$result=JdSkillUpdater::find($skill_id);
		$result->skill_update_status=1;
		$result->save();
		$details = JdSkillUpdater::with('user')->where("skill_id",$skill_id)->first();
		try{
			Mail::to('requests@hashroot.com')->send(new SkillUpdate($details));
		} catch (\Exception $e) {
			Log::info( "skill status update  mail:".$e->getMessage());
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

	public function getWorkReport(Request $request){
		$date =$request->date_of_report;
		$user_id =$request->user_id;
		$date = strtotime($date);
		$start_date = date('Y-m-d 00:00:00', $date);
		$start_date = strtotime($start_date);
		$end_date = date('Y-m-d 23:59:00', $date);
		$end_date = strtotime($end_date);

		$attendance_log = AttendanceLog::get_dailyStatus_with_date($user_id, $start_date, $end_date);
		if(!$attendance_log){
			return response()->json([
				'status' => false,
				'message' => 'Sorry no records found'
			], 200);
		}

		$att_id = $attendance_log[0]['att_id'];
		$result =TicketDetails::get_ticket_details($user_id, $att_id, $date, 'own_work_report');
	//	dd($result );
		return response()->json([
            'data' => $result,
            'message' => 'Success'
        ], 200);
	}


	 /**
     * Store a newly created ticket count.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ticketSaveBox(Request $request){
		$dept=User::where('id', $request->user_id)->first();
		//dd($dept);
		$daily_data_id_pend     = $request->daily_act_id_pend;
		$daily_data_id_resol     = $request->daily_act_id_resol;
		if($dept->dep_id==52){
			$daily_data_id_pend     = 710;
			$daily_data_id_resol     =711;

		}
		if($dept->dep_id==2){
			$daily_data_id_pend     = 601;
			$daily_data_id_resol     =600;

		}
		$att_id           	   =$request->att_id;
	
		$daily_input_pend        = $request->daily_inputValue_pend;
		$daily_input_resol         = $request->daily_inputValue_resol ;
		//dd($daily_data_id_resol );
    

			$daily_data_id     = $request->daily_act_id_pend;
			if($dept->dep_id==52){
				$daily_data_id     = 710;
	
			}
			if($dept->dep_id==2){
				$daily_data_id     = 601;
	
			}//dd($daily_data_id   );
			$att_id           	   =$request->att_id;
			$daily_input        = $request->daily_inputValue_pend;
		$user_id    = $request->user_id;
		$ticket_details_a['ticket_url'] =$request->ticket_url;
		$ticket_details_a['ticket_response'] =$request->ticket_response;
		$ticket_details_a['ticket_sla']  =$request->ticket_sla;
		if(	$ticket_details_a['ticket_url'] && 	$ticket_details_a['ticket_response'] && $ticket_details_a['ticket_sla'] ){
			$details_update_res = $this->update_ticket_details($ticket_details_a,$user_id);
			if($details_update_res == false){
				$this->jsonOutput(['status' => false, 'message' => 'Ticket details update failed']);
			}
		}
		$user_id 											   = $request->user_id;
		$daily_datas_stat  			    				   = AttendanceLog::get_dailyStatus($user_id);
		$unser_data        			  					    = unserialize($daily_datas_stat['0']['work_report']);	
		$sla_violation										 = $daily_datas_stat['0']['sla_violation'];
		$unser_data[$daily_data_id]['status']	  = 1;
		$daily_input   										  = $this->clean($daily_input);
		$unser_data[$daily_data_id]['reply'] 	   = $daily_input;
		$unser_data[$daily_data_id]['time'] 	  = strtotime('now');
		$ser_dat      										   = serialize($unser_data);	
		$result['sla']										  = 0;
		if($ticket_details_a['ticket_sla']=="30 - 35 min" || $ticket_details_a['ticket_sla']=="35 - 40 min" || $ticket_details_a['ticket_sla']=="40 - 45 min" || $ticket_details_a['ticket_sla']=="45 - 50 min" || $ticket_details_a['ticket_sla']=="50 - 55 min" || $ticket_details_a['ticket_sla']=="55 - 60 min" || $ticket_details_a['ticket_sla']=="above 1 hour"){
			$ser['sla_violation']							= $sla_violation+1;
			$result['sla']										=$ser['sla_violation'];
		}
		$ser['work_report']								  = $ser_dat; 
		$result	=AttendanceLog::where('user_id',$user_id)->where('att_status',0)->first();
		$result->work_report=$ser['work_report'];
		$result->save();
		$this->ticketSaveResol( $request);
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

	public function ticketSaveResol($request){
		$daily_data_id     = $request->daily_act_id_resol;
		$dept=User::where('id', $request->user_id)->first();

		//dd($daily_data_id_resol );
		if($dept->dep_id==52){
			$daily_data_id     = 709;

		}
		if($dept->dep_id==2){
			$daily_data_id     = 600;

		}
			$att_id           	   =$request->att_id;
			$daily_input        = $request->daily_inputValue_resol;
		$user_id    = $request->user_id;
		$ticket_details_a['ticket_url'] =$request->ticket_url;
		$ticket_details_a['ticket_response'] =$request->ticket_response;
		$ticket_details_a['ticket_sla']  =$request->ticket_sla;
		if(	$ticket_details_a['ticket_url'] && 	$ticket_details_a['ticket_response'] && $ticket_details_a['ticket_sla'] ){
			$details_update_res = $this->update_ticket_details($ticket_details_a,$user_id);
			if($details_update_res == false){
				$this->jsonOutput(['status' => false, 'message' => 'Ticket details update failed']);
			}
		}
		$user_id 											   = $request->user_id;
		$daily_datas_stat  			    				   = AttendanceLog::get_dailyStatus($user_id);
		$unser_data        			  					    = unserialize($daily_datas_stat['0']['work_report']);	
		$sla_violation										 = $daily_datas_stat['0']['sla_violation'];
		$unser_data[$daily_data_id]['status']	  = 1;
		$daily_input   										  = $this->clean($daily_input);
		$unser_data[$daily_data_id]['reply'] 	   = $daily_input;
		$unser_data[$daily_data_id]['time'] 	  = strtotime('now');
		$ser_dat      										   = serialize($unser_data);	
		$result['sla']										  = 0;
		if($ticket_details_a['ticket_sla']=="30 - 35 min" || $ticket_details_a['ticket_sla']=="35 - 40 min" || $ticket_details_a['ticket_sla']=="40 - 45 min" || $ticket_details_a['ticket_sla']=="45 - 50 min" || $ticket_details_a['ticket_sla']=="50 - 55 min" || $ticket_details_a['ticket_sla']=="55 - 60 min" || $ticket_details_a['ticket_sla']=="above 1 hour"){
			$ser['sla_violation']							= $sla_violation+1;
			$result['sla']										=$ser['sla_violation'];
		}
		$ser['work_report']								  = $ser_dat; 
		$result	=AttendanceLog::where('user_id',$user_id)->where('att_status',0)->first();
		$result->work_report=$ser['work_report'];
		$result->save();

	
		
	}
}
