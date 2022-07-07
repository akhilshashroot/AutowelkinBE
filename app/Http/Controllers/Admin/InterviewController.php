<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use DB;
use Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\User;
use App\Mail\InterviewMail;
use Illuminate\Support\Facades\Mail;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::where('is_active', 1)->orderBy('time','desc')->get();
        $exam_data=array();
		$exam_datas=array();
		foreach($exams as $item) {
            $exam_data['id']=$item->id;
            $exam_data['interview_date'] =$item->exam_date;
            $exam_data['name'] =$item->candidate_name;
            $exam_data['position'] =$item->position;
            $exam_data['ctc'] =$item->current_salary;
            $exam_data['etc'] =$item->expected_salary;
            $exam_data['phone'] =$item->candidate_phone;
            $exam_data['candidate_email'] =$item->candidate_email;
            $exam_data['notice_period'] =$item->notice_period;
            if($item->status=="offered"){
                $exam_data['status'] ="DOJ : ".$item->joining_date;
            }else{
                $exam_data['status'] =$item->status;

            }
            $exam_data['status_edit'] =$item->status;
            $exam_data['joining_date']=($item->joining_date)?date('Y-m-d', strtotime($item->joining_date)):'';
            $exam_data['mode'] =$item->mode;
            $exam_data['priority']   =$item->priority;
            $exam_data['resume']  = 'https://one.hashroot.com/server/storage/app/public/Resume/'.$item->resume;
            $exam_data['note']=$item->comments;
            if(!empty($item->examiners_details)){
               $examiners_data=  unserialize($item->examiners_details);
               $user_idar=array();
               $user_ids=array();
           // $exam_data['examiners_details']=  $examiners_data;
            foreach($examiners_data as $users){
                $user_idar['id']= $users['user_id'];
                $user_name=User::find($users['user_id']);
                $user_idar['fullname']= isset($user_name)?$user_name->fullname:'';
                array_push($user_ids,  $user_idar);
                
            }    
            $exam_data['pr_userids']= $user_ids;

        
          }else{
                $exam_data['pr_userids']= "";
            }
            if(!empty($item->comment_array)){

                $comment_data=  unserialize($item->comment_array);
              
             $exam_data['comment']=  $comment_data;
             }else{
                 $exam_data['comment']= "";
             }
 

       array_push( $exam_datas, $exam_data);
           // to know what's in $item
       }
        return response()->json([
            'data' => $exam_datas,
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
        //    'examiner' => 'required',
            'candidate_name' => 'required',
            'candidate_email' => 'required|email',
            'candidate_phone' => 'required',
            'notice_period' => 'required',
            // 'expected_salary' => 'required',
            // 'current_salary' => 'required',
            // 'interview_status' => 'required',  
            'resume' => 'mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream,application/pdf' ,
             'candidate_position' => 'required',
             'interview_mode' => 'required',
             "creator_id"=> 'required',
        ]);
        
		 $creator_id = $request->creator_id;
		
		if( $request->interview_date != ''){
			
			$insert_a['exam_date']       = $request->interview_date;
			$insert_a['exam_date_str']   = strtotime( $request->interview_date);
		}
		else{
			$insert_a['exam_date'] = NULL;
			$insert_a['exam_date_str'] = NULL;
		}
		
		$examiners_list_ids          = explode(',', $request->examiner);
		$insert_a['candidate_name']  = $request->candidate_name;
		$insert_a['candidate_email'] = $request->candidate_email;
		$insert_a['candidate_phone'] = $request->candidate_phone;
		$insert_a['notice_period']   = $request->notice_period;
		$insert_a['expected_salary'] =  $request->expected_salary;
		$insert_a['current_salary']  = $request->current_salary;
		$insert_a['comments']        = $request->note;
		$insert_a['status']          = $request->interview_status;
		$insert_a['mode']            =  $request->interview_mode;
		$insert_a['position']        = $request->candidate_position;
		$insert_a['time']        	 = time();
		$priority                    =  $request->priority;

		if($priority){
			$insert_a['priority']    = 1;
		}
		else{
			$insert_a['priority']    = 0;
		}

		$insert_a['creator']         = $creator_id;

		// print_r($insert_a['creator']);
			

		if($insert_a['candidate_email'] != ''){
			$mail_status = Exam::checkMailUnique($insert_a['candidate_email']);
			if($mail_status){
			
                return response()->json([
                    'status' => false,
                    'error' => 'Email Already Exists! Please try with another!'
                ], 400);
			}
		}

		

		if( $request->joining_date != ''){
			$insert_a['joining_date']      =  $request->joining_date;
			$insert_a['joining_date_str']  = strtotime($request->interview_date);
		}
		else{
			$insert_a['joining_date']      = NULL;
			$insert_a['joining_date_str']  = NULL;
		}

        $empl_details = array();
		$examiners_details = array();
		if(count($examiners_list_ids)>0 && $request->examiner && $request->examiner !="null"){
			foreach ($examiners_list_ids as $examiners_id){
				array_push($empl_details,Exam::get_examiners_details($examiners_id));
				$examiner_dets = Exam::get_examiners_details($examiners_id);
			$examiner_details = array("user_id"=>$examiner_dets->id, "email"=>$examiner_dets->email, "dep_id"=>$examiner_dets->dep_id);  
				// $examiners_details[$examiner_dets->user_id] = $examiner_details;
				array_push($examiners_details,$examiner_details);
			}
			$insert_a['examiners_details']   = serialize($examiners_details);
		}
		else{
			$insert_a['examiners_details']   = NULL;
		}

		/** get creator email */
		$get_creator   = Exam::get_creator_interview($creator_id);
		$creator_email = $get_creator->email;
		
		

		$interviewers_emails_array = array();
		$interviewers_name_array   = array();

		foreach ($empl_details as $emp_det){
			array_push($interviewers_emails_array,$emp_det->email);
			array_push($interviewers_name_array,$emp_det->fullname);
		}

		$interviewer_names = implode(',', $interviewers_name_array);
		$insert_a['examiners']         = $interviewer_names;

		$interviewer_emails = implode(',', $interviewers_emails_array);
        $insert_a['resume'] ="";
        if($request->has('resume')){
            $file = $request->file('resume');
            $exte = $file->extension();
            $newFileName = str_replace(' ', '', $insert_a['candidate_name']).strtotime('now').'_cv';
            $path = $file->storeAs('public/Resume',$newFileName.".".$exte);
            $insert_a['resume'] =  $newFileName.'.'.$exte ;
              }
		/** close resume upload */
        switch($insert_a['status']) {
			case "open":
				// $this->email->to('bibin.varghese@hashroot.com');
			//	$this->email->to('hr@hashroot.com');

				break;
			case "1st interview scheduled":
				// if($creator_id == 1){ 
				// 	$creator_email = 'anees@hashroot.com';
				// 	$this->email->to($creator_email.','.$interviewer_emails);
				// }
				// else if($creator_id == 3 || $creator_id == 9 ){
				// 	$this->email->to($interviewer_emails.',hr@hashroot.com');
				// }
				// else{
				// 	$this->email->to($creator_email);
				// 	// $this->email->to($creator_email.',hr@hashroot.com');
				// }
				break;
			case "2nd interview scheduled":
				// if($creator_id == 1){
				// 	$creator_email = 'anees@hashroot.com';
				// 	$this->email->to($creator_email.','.$interviewer_emails);
				// }
				// else if($creator_id == 3 || $creator_id == 9){
				// 	$this->email->to($interviewer_emails.',hr@hashroot.com');
				// }
				// else{
				// 	// $this->email->to($creator_email);
				// 	$this->email->to($creator_email.',hr@hashroot.com');
				// }
				break;
			case "for review":
				// $subject = "For Review ".$insert_a["candidate_name"];
				// $message_content = "The candidate, ".$insert_a["candidate_name"]." interviewed on ".$insert_a["exam_date"]. " for the position ".$insert_a["position"]." ,is selected for your review. Please add your review after closely examining the interviewers' comments and salary expectation and change the status accordingly";
				// if($creator_id == 1){
				// 	$creator_email = 'anees@hashroot.com';
				// 	$this->email->to($creator_email.','.$interviewer_emails);

				// }
				// else if($creator_id == 3 || $creator_id == 9){
				// 	$this->email->to($interviewer_emails.',hr@hashroot.com');
				// }
				// else{
				// 	// $this->email->to($creator_email);
				// 	$this->email->to($creator_email.',hr@hashroot.com');
				// }
				break;
		}

        $insert_a =  json_decode( json_encode($insert_a), true);

        $result=DB::table('exam')->insert($insert_a);

        if($result) {

            try {

             $test=   Mail::send(new InterviewMail(  $insert_a,$interviewer_emails,$interview_function=""));
               //   Log::info( $test)   ;
               } catch (\Exception $e) {
                     
                $e->getMessage();
                Log::info( $e->getMessage());  
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
    public function interviewUpdate(Request $request, $id)
    {
       
        $email_status = Exam::where('id', $id)->first();
		 $creator_id = $request->creator_id;
		if( $request->interview_date != ''){
			
			$insert_a['exam_date']       = $request->interview_date;
			$insert_a['exam_date_str']   = strtotime( $request->interview_date);
		}
	
		$examiners_list_ids          = explode(',', $request->examiner);
        if($request->candidate_name){
           $insert_a['candidate_name']  = $request->candidate_name;
        }
        if($request->candidate_email){
            $insert_a['candidate_email'] = $request->candidate_email;

        }
        if($request->candidate_phone){
            $insert_a['candidate_phone'] = $request->candidate_phone;

        }
        if( $request->notice_period){
            $insert_a['notice_period']   = $request->notice_period;

        }
        if( $request->expected_salary){
            $insert_a['expected_salary'] =  $request->expected_salary;

        }
        if($request->current_salary){
            $insert_a['current_salary']  = $request->current_salary;

        }
        if( $request->comments){
            $insert_a['comments']        = $request->comments;

        }
        if($request->interview_status){
            $insert_a['status']          = $request->interview_status;

        } if( $request->interview_mode){
            $insert_a['mode']            =  $request->interview_mode;

        } if( $request->candidate_position){
            $insert_a['position']        = $request->candidate_position;

        }
            $insert_a['time']        	 = time();
       
		$priority                    =  $request->priority;

		if($priority){
			$insert_a['priority']    = 1;
		}
		else{
			$insert_a['priority']    = 0;
		}
         if( $creator_id ){
            $insert_a['creator']         = $creator_id;
         }

			
		if($request->candidate_email != '' && $request->candidate_email !=$email_status->candidate_email){
			$mail_status = Exam::checkMailUnique($insert_a['candidate_email']);
			if($mail_status){
			
                return response()->json([
                    'status' => false,
                    'message' => 'Email Already Exists! Please try with another!'
                ], 200);
			}
		}

		
		if( $request->joining_date != ''){
			$insert_a['joining_date']      =  $request->joining_date;
			$insert_a['joining_date_str']  = strtotime($request->interview_date);
		}


        $empl_details = array();
		$examiners_details = array();
       
        if(!$examiners_list_ids){
            $examiners_list_ids=array();
        }
		if(count($examiners_list_ids)>0 && $request->examiner && $request->examiner !="null"){
			foreach ($examiners_list_ids as $examiners_id){
				array_push($empl_details,Exam::get_examiners_details($examiners_id));
				$examiner_dets = Exam::get_examiners_details($examiners_id);
			$examiner_details = array("user_id"=>$examiner_dets->id, "email"=>$examiner_dets->email, "dep_id"=>$examiner_dets->dep_id);  
				// $examiners_details[$examiner_dets->user_id] = $examiner_details;
				array_push($examiners_details,$examiner_details);
			}
			$insert_a['examiners_details']   = serialize($examiners_details);

            $interviewers_emails_array = array();
            $interviewers_name_array   = array();
    
            foreach ($empl_details as $emp_det){
                array_push($interviewers_emails_array,$emp_det->email);
                array_push($interviewers_name_array,$emp_det->fullname);
            }
    
            $interviewer_names = implode(',', $interviewers_name_array);
            $insert_a['examiners']         = $interviewer_names;
    
            $interviewer_emails = implode(',', $interviewers_emails_array);
		}
		
        if( $creator_id){

		/** get creator email */
		$get_creator   = Exam::get_creator_interview($creator_id);
      //  dd($get_creator );
		$creator_email = $get_creator->email;
		
        }

     //   $insert_a['resume'] ="";

        if($request->resume!="null"){
            $file = $request->file('resume');
            $exte = $file->extension();
            $newFileName = str_replace(' ', '', $insert_a['candidate_name']).strtotime('now').'_cv';
            $path = $file->storeAs('public/Resume',$newFileName.".".$exte);
            $insert_a['resume'] =  $newFileName.'.'.$exte ;
              }
		/** close resume upload */

        // switch($insert_a['status']) {
		// 	case "open":
		// 		// $this->email->to('bibin.varghese@hashroot.com');
		// 		$this->email->to('hr@hashroot.com');

		// 		break;
		// 	case "1st interview scheduled":
		// 		if($creator_id == 1){
		// 			$creator_email = 'anees@hashroot.com';
		// 			$this->email->to($creator_email.','.$interviewer_emails);
		// 		}
		// 		else if($creator_id == 3 || $creator_id == 9 ){ 
		// 			$this->email->to($interviewer_emails.',hr@hashroot.com');
		// 		}
		// 		else{
		// 			$this->email->to($creator_email); 
		// 			// $this->email->to($creator_email.',hr@hashroot.com');
		// 		}
		// 		break;

		// 	case "2nd interview scheduled":
		// 		if($creator_id == 1){
		// 			$creator_email = 'anees@hashroot.com';
		// 			$this->email->to($creator_email.','.$interviewer_emails);
		// 		}
		// 		else if($creator_id == 3 || $creator_id == 9){
		// 			$this->email->to($interviewer_emails.',hr@hashroot.com');
		// 		}
		// 		else{
		// 			// $this->email->to($creator_email);
		// 			$this->email->to($creator_email.',hr@hashroot.com');
		// 		}
		// 		break;

		// 	case "for review":
		// 		$subject = "For Review - ".$update_a["candidate_name"];
		// 		$message_content = "The candidate, ".$update_a["candidate_name"]." interviewed on ".$update_a["exam_date"]. ", for the position '".$update_a["position"]."' is selected for your review. Please add your review after closely examining the interviewer's comments and salary expectation and change the status accordingly";
		// 		if($creator_id == 1){
		// 			$creator_email = 'anees@hashroot.com';
		// 			$this->email->to($creator_email.','.$interviewer_emails);

		// 		}
		// 		else if($creator_id == 3 || $creator_id == 9){
		// 			$this->email->to($interviewer_emails.',hr@hashroot.com');
		// 		}
		// 		else{
		// 			// $this->email->to($creator_email);
		// 			$this->email->to($creator_email.',hr@hashroot.com');
		// 		}
		// 		break;
		// 	}

            if($email_status->status != $request->interview_status){
                try {
    
                    Mail::send(new InterviewMail(  $insert_a,$interviewer_emails,$interview_function="update"));
                         
                   } catch (\Exception $e) {
                         
                    $e->getMessage();
                   }
            }
     
        $insert_a =  json_decode( json_encode($insert_a), true);

        $result=DB::table('exam')->where('id', $id)->update($insert_a);

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
       
         $result = Exam::where('id',$id)->update(['is_active'=>0]);
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
    public function download($id)
    {
       
        $pdf = Exam::findOrfail($id);
        if(!$pdf){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
//         $file = Storage::disk('public')->get($pdf->resume);
//         return (new Response($file, 200))
//         ->header('Content-Type','application/pdf');
        $filename = 'app/public/Resume/' . $pdf->resume;
// }
		
        $exists = storage_path($filename);


        // return Response::make(file_get_contents( $exists), 200, [

        //     'Content-Type'
        // => 'application/pdf',

        // 'Content-Disposition' => 'inline; filename="'.$filename.'"'

        // ]);



        $foo = \File::extension($filename);
        if($foo=="pdf"){
        return Response::make(file_get_contents( $exists), 200, [
        
            'Content-Type'
        => 'application/pdf',
        
           'Content-Disposition' => 'inline; filename="'.$filename.'"'
        
        ]);
        }
                
        else{
               $content_types = [
                 'application/octet-stream', // txt etc
                 'application/msword', // doc
                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
                 'application/vnd.ms-excel', // xls
                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
                
             ];
        
            return Response::make(file_get_contents( $exists), 200, [
        
            'Content-Type'=>  $content_types,
        
           'Content-Disposition' => 'inline; filename="'.$filename.'"'
        
        ]);
                    
      }
            
}

   public function add_new_comment(Request $request){
       $candidate_id		=	 $request->candidate_id;
      
       $comment		     	=	 $request->comment;
       $admin_id			=	 $request->admin_id;
       $exam 		   = Exam::where('id', $candidate_id)->first();
       $userdata=Admin::where('id', $admin_id)->first();
       $newComment = array('comment' => $comment,
                                           'time'=>date("d F Y  h:i A"),
                                           "name"=>$userdata['name']);
   
       $comments 		   = Exam::find($candidate_id);
       if(!empty($comments->comment_array)){
           $comments	   =	unserialize($comments->comment_array);
           
       }else{
           $comments=[];	
       }
       array_push($comments,$newComment);
       $update_data=serialize($comments);

       $exam->comment_array =$update_data;
       $result = $exam->save();
   //    $result =Exam::where('id',$candidate_id)->update(['comment_array'=> $update_data]);
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
