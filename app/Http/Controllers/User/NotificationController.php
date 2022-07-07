<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\linkedinNotify;
class NotificationController extends Controller
{
    Public function notification(Request $request){ 	

		if($request->linkd_notify){ 

                $result=new linkedinNotify();
				$result->not_user = $request->user_id;
				$result->not_status =1;
                $result->save();
                $data['user_id']=$request->user_id;
				$data['status']=1;
                
        }else{

				$data['user_id']=$request->user_id;
				$data['status']=0;

		} 
            return response()->json([
                'data' =>$data,
                'message' => 'Success'
            ], 200);
       

	}

}