<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\User;
use DB;
use App\Mail\UserTaskMail;
use App\Mail\UserTaskCommentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {

        $user_id    =$id;
     	
       //task_assigned_to_others
        $data= Assignment::leftjoin('users','users.id','=','assignments.assign_to')
			           ->where('creator_id',$user_id)
			           ->orderby('asgnmnt_id','desc')->get();
                $task_array1['task_to_others']=array();
                $task_array1['my_task']=array();
                $task_array1['completed_task']=array();
              foreach( $data as $taskdata){
                $task1['id']=$taskdata->asgnmnt_id;
                $task1['title']=$taskdata->title;
                $task1['assignee']=$taskdata->fullname;
                $task1['status'] =$taskdata->status;
                $task1['deadline']=$taskdata->date;
                $task1['task_attachment']=($taskdata->task_attachment)?  env('APP_URL').'/storage/tasks/'.$taskdata->task_attachment:'';
                $task1['comment_attachment']=($taskdata->comment_attachment)?  env('APP_URL').'/storage/tasks/'.$taskdata->comment_attachment:'';
                $task1['assigner']=(User::find($taskdata->creator_id))?User::find($taskdata->creator_id)->fullname:'Admin';
                $task1['task_description']=$taskdata->body;
                $task1['date']=$taskdata->time_stamp;
                $task1['comment'] =	unserialize($taskdata->comments);
                array_push($task_array1['task_to_others'], $task1);

              }
            //my task in progress
        $data1= Assignment::leftjoin('users','users.id','=','assignments.assign_to')
                           ->where('assign_to',$user_id)
                         //  ->where('status',0)
                           ->orderby('asgnmnt_id','desc')->get();
                foreach( $data1 as $taskdata){
                    if($taskdata->status==0){
                        $task2['id']=$taskdata->asgnmnt_id;
                        $task2['title']=$taskdata->title;
                        $task2['assignee']=$taskdata->fullname;
                        $task2['status'] =$taskdata->status;
                        $task2['deadline']=$taskdata->date;
                        $task2['task_attachment']=($taskdata->task_attachment)?  env('APP_URL').'/storage/tasks/'.$taskdata->task_attachment:'';
                        $task2['comment_attachment']=($taskdata->comment_attachment)?  env('APP_URL').'/storage/tasks/'.$taskdata->comment_attachment:'';
                        $task2['assigner']=(User::find($taskdata->creator_id))?User::find($taskdata->creator_id)->fullname:'Admin';
                        $task2['task_description']=$taskdata->body;
                        $task2['date']=$taskdata->time_stamp;
                        $task2['comment'] =	unserialize($taskdata->comments);
                        array_push($task_array1['my_task'], $task2);
                    }else{
                        $task2['id']=$taskdata->asgnmnt_id;
                        $task2['title']=$taskdata->title;
                        $task2['assignee']=$taskdata->fullname;
                        $task2['status'] =$taskdata->status;
                        $task2['deadline']=$taskdata->date;
                        $task2['task_attachment']=($taskdata->task_attachment)?  env('APP_URL').'/storage/tasks/'.$taskdata->task_attachment:'';
                        $task2['comment_attachment']=($taskdata->comment_attachment)?  env('APP_URL').'/storage/tasks/'.$taskdata->comment_attachment:'';
                        $task2['assigner']=(User::find($taskdata->creator_id))?User::find($taskdata->creator_id)->fullname:'Admin';
                        $task2['task_description']=$taskdata->body;
                        $task2['date']=$taskdata->time_stamp;
                        $task2['comment'] =	unserialize($taskdata->comments);
                        array_push($task_array1['completed_task'], $task2);
                    }
                }

        // $data		=Assignment::getOwnTaskList($user_id);
        // dd(  $data);
        return response()->json([
            'data' => $task_array1,
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
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required',
            'period' => 'required',
            'employee_id'=> 'required',
        ]);
           $user_id 				 =	 $request->user_id;
           $title					 =	 $request->title;
           $body					 =	 $request->body;
           $assign_to		   		 =	 $request->employee_id;
           $period		   			 =	 $request->period;
           $date		   			 =	 $request->date;
           if(!$date){
            $date			=	0;
        }
        $comments   = array(); 
        $comments	= serialize($comments);
        $task=new Assignment;
        $task->title      = $title;
        $task->body       = $body;
        $task->creator_id = $user_id;
        $task->period     = $period;
        $task->assign_to  = $assign_to;
        $task->comments   = $comments;
        $task->date       = $date;
        $task->status     = 0;
        $task_attachment ="";
      //  dd($request->attachment);
        if($request->attachment!="null"){
            $file = $request->file('attachment');
            $exte = $file->extension();
            $newFileName = "attachment";
            $path = $file->storeAs('public/tasks',trim($newFileName).strtotime('now').".".$exte);
            $task_attachment = $newFileName.strtotime('now').'.'.$exte ;
              }
        $task->task_attachment =  $task_attachment ;
        $mail_data[]=$task;
      
        $task->save();
        if($task) {
            try {

            Mail::send(new UserTaskMail($mail_data));
              
            } catch (\Exception $e) {
              
             Log::info( "user task  mail:".$e->getMessage());

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

    function post_comment(Request $request){

        $validated = $request->validate([
            'comment' => 'required',
            'status' => 'required',
            "user_id" => 'required',
            "task_id" => 'required',

        ]);
        $user_id 				=	$request->user_id;
        $asgnmnt_id				=	$request->task_id;
        $comment				=	$request->comment;
        $status					=	$request->status;
         if($status==1){
             $status=1;
         }else{
            $status=0;
         }
        $data   = Assignment::find($asgnmnt_id);
        $getComment  = unserialize($data->comments);  
        $newComment['date']						= 	 date("d M Y h:i:s a");
        $newComment['time_stamp']				=	strtotime("now");
        $newComment['comments']					=	$comment;
        $newComment['status']					=	$status;
        $newComment['name']						=	User::find($user_id)->fullname;
        $mail_data[]=$data;
        $comment_data[]=$newComment;
        array_push($getComment,$newComment);
        
        $serializeComment	=	serialize($getComment);
        // $serializeComment	=	["comments"=>$serializeComment,"status"=>$status];
        // $updateStatus		   =	 $this->User_model->updateTaskComment($asgnmnt_id,$serializeComment);
        $data->comments=$serializeComment;
        $data->status=$status;
        if($request->attachment!="null"){
            $file = $request->file('attachment');
            $exte = $file->extension();
            $newFileName = "attachment";
            $path = $file->storeAs('public/tasks',trim($newFileName).strtotime('now').".".$exte);
            $task_attachment = $newFileName.strtotime('now').'.'.$exte ;
            $data->comment_attachment =  $task_attachment ;
        }
        
        $data->save();
        if($data) {
            try {

                Mail::send(new UserTaskCommentMail($mail_data,$comment_data,$user_id));
                  
                } catch (\Exception $e) {
                  
                Log::info( "user task comment mail:".$e->getMessage());
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
