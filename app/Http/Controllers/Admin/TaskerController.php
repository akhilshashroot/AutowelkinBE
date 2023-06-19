<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Admin;
use App\Models\User;
use DB;
use App\Mail\TaskAssignment;
use App\Mail\AdminTaskComment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Auth;
class TaskerController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($admin_id)
    {
       // $data				    = $this->session->userdata();
      
     //  $admin_id=$admin_id;
		$data['taskListOwn']	=Assignment::getTaskList( $admin_id);
		if($data['taskListOwn'] != ""){
			foreach($data['taskListOwn'] as $key=>$value){
				if($data['taskListOwn'][$key]->task_attachment == ""){
					$data['taskListOwn'][$key]->task_attachment = "";
				}
				else{
					$data['taskListOwn'][$key]->task_attachment = ($value->task_attachment)? env('APP_URL').'storage/tasks/'.$value->task_attachment:"";
				}
                if($data['taskListOwn'][$key]->comment_attachment == ""){
					$data['taskListOwn'][$key]->comment_attachment = "";
				}
				else{
					$data['taskListOwn'][$key]->comment_attachment = ($value->comment_attachment)? env('APP_URL').'storage/tasks/'.$value->comment_attachment:"";
				}
                $data['taskListOwn'][$key]->comments=unserialize($value->comments);
                $assign=Admin::where('id', $data['taskListOwn'][$key]->creator_id)->first();
                $data['taskListOwn'][$key]['assigner']=isset($assign)?$assign->name:'';
			}
		}
		$data['tasklistOthers'] = Assignment::getTasklistOthers();

		if($data['tasklistOthers'] != ""){
			foreach($data['tasklistOthers'] as $key=>$value){
				if($data['tasklistOthers'][$key]->task_attachment == ""){
					$data['tasklistOthers'][$key]->task_attachment = "";
				}
				else{
					$data['tasklistOthers'][$key]->task_attachment = ($value->task_attachment)?  env('APP_URL').'storage/tasks/'.$value->task_attachment:"";
				}
                if($data['tasklistOthers'][$key]->comment_attachment == ""){
					$data['tasklistOthers'][$key]->comment_attachment = "";
				}
				else{
					$data['tasklistOthers'][$key]->comment_attachment = ($value->comment_attachment)?  env('APP_URL').'storage/tasks/'.$value->comment_attachment:"";
				}
                Log::info($value->asgnmnt_id );
                $data['tasklistOthers'][$key]->comments=unserialize($value->comments);

			}
		}
	//	$data['employee_list'] 	= $this->Admin_model->getTeamData();
        return response()->json([
            'data' => $data,
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
        $validated = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required',
            'period' => 'required',
            'date' => 'required',
        ]);
        $creater_id=$request->creater_id;
        $title						=	 $request->title;
		$body					    =	 $request->body;
		$assign_to		   		    =	 $request->user_id;
		$period		   			    =	 $request->period;
		$date		   			    =	 $request->date;


		/**
	 * starts Multiple attachments
	 */
    $task_attachment="";
    // if($request->hasfile('attachment'))
    // {
    //    foreach($request->file('attachment') as $key => $file)
    //    {
    //        $path = $file->store('public/tasks');
    //        $name = $file->getClientOriginalName();

    //      //  $insert[$key]['name'] = $name;
    //      $task_attachment['path'] = $path;

    //    }
    // }

    if($request->attachment !="undefined"){
        $file = $request->file('attachment');
        log::info( $file);
        $exte = $file->extension();
        $newFileName = "attachment";
        $path = $file->storeAs('public/tasks',trim($newFileName).strtotime('now').".".$exte);
        $task_attachment = $newFileName.strtotime('now').'.'.$exte ;
          }
/** -------ends multiple attachment--------- */
          $comments  			  = array(); 
          $comments			  = serialize($comments);
          $insert_assignment = [
                                          'title'         => $title	,
                                          'body'        => $body,
                                          'creator_id' => $creater_id,
                                          'status'      =>0,
                                          'period'      =>$period,
                                          'assign_to' =>$assign_to,
                                          'comments'=>$comments,
                                          'date'		   =>$date,
                                          'task_attachment'=>$task_attachment
                               ];
            $date_text = "";
          switch ($period) {
			case 'ONE':
                $period_text = "one time ";
				$date = date('d F Y',strtotime($date));
				$date_text	= "<h4> Deadline : </h4><p>".$date."</p>";
				break;
			case 'DAY':
                $period_text =	"daily";# code...
				break;
			case 'WEEK':
                $period_text =	"weekly";# code...
				break;
			case 'MONTH':
                $period_text  = "Monthly";# code...
				break;
			default:
                $period_text = "not specified";
				break;
		}
        $admin = Admin::find($creater_id);
          $userdata = User::find($assign_to);
          $maildata['assignee'] = $userdata->fullname;
          $maildata['period_text'] = $period_text;
          $maildata['task_creator'] = $admin->name;
          $maildata['date_created'] = date("d M Y h:i:sa");
          $maildata['title'] = $title;
          $maildata['body'] = $body;
          $maildata['date_text'] = $date_text;

        $insert_assignment =  json_decode( json_encode($insert_assignment), true);
        $tasker = Assignment::create(  $insert_assignment );
        try{
            if($userdata->id==839){
                Mail::to('hr@hashroot.com')->send(new TaskAssignment($maildata));

            }else{
                Mail::to($userdata->email)->send(new TaskAssignment($maildata));

            }
		} catch (\Exception $e) {
			Log::info( "task assignment mail:".$e->getMessage());
		}

        
        if($tasker) {
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
    public function taskUpdate(Request $request)
    {
        	//$user_id 				 =	 $this->session->userdata('user_id');
		$asgnmnt_id				=	$request->task_id;
		$comment				=	$request->comment;
        $creater_id=$request->creater_id;
		$status					 =	$request->status;
		 if($status){
			 $status=1;
		 }else{
			$status=0;
		 }
		$admin_name=Admin::where('id', $creater_id)->first();
        if(!$admin_name){
            $admin_name= Auth::user();
        }
		$data		=Assignment::select('assignments.*','users.fullname as name', DB::raw('DATE_FORMAT(time_stamp, "%d-%M-%Y %h:%i %p, %a") as realDate'))
                               ->leftJoin('users','users.id','=','assignments.assign_to')
                              
		                       ->where('asgnmnt_id',$asgnmnt_id)->first();

        $mail_data[]=$data;
		$getComment  = unserialize($data->comments);

		$newComment['date']						= 	 date("d M Y h:i:s a"); 
		$newComment['time_stamp']				=	strtotime("now");
		$newComment['comments']					=	$comment;
		$newComment['status']					=	$status;
		$newComment['name']						=$admin_name->name;
		array_push($getComment,$newComment);
        $comment_data[]=$newComment;
		$serializeComment	=	serialize($getComment);
		// $serializeComment   =	["comments"=>$serializeComment,"status"=>$status];
		// $updateStatus		   =   $this->Admin_model->updateTaskComment($asgnmnt_id,$serializeComment);

        $assgn = Assignment::find($asgnmnt_id);
        $assgn->status =$status;
        $assgn->comments =$serializeComment;
        if($request->attachment!="null"){
            $file = $request->file('attachment');
            $exte = $file->extension();
            $newFileName = "attachment";
            $path = $file->storeAs('public/tasks',trim($newFileName).strtotime('now').".".$exte);
            $task_attachment = $newFileName.strtotime('now').'.'.$exte ;
            $assgn->comment_attachment =  $task_attachment ;
        }
        $assgn->save();   

        try {

            Mail::send(new AdminTaskComment($mail_data,$comment_data,$creater_id));
              
            } catch (\Exception $e) {
              
            Log::info( "user task comment mail:".$e->getMessage());
            }
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $result = Assignment::where('asgnmnt_id',$id)->delete();
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
