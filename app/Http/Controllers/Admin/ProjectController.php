<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\ProjectRoom;
use App\Models\ProjectRoomUser;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = ProjectRoom::orderBy('pr_id','asc')->get();
        $set_value=array();
        $prjct_data=array();
      
        foreach($projects as $value){
            $user_dataar=array();
            $user_idar=array();
            $user_ids=array();

            $set_value["pr_id"]=$value['pr_id'];
            $set_value["pr_name"]=$value['pr_name'];
            $set_value["pr_description"]=$value['pr_description'];
            $user_data=  unserialize($value['pr_userids']);
            foreach($user_data as $users){
                $user_dataar[]= $users['name'];
                if($users['role'] !=="admin"){
                $user_idar['id']= $users['userid'];
                $user_idar['fullname']= $users['name'];
                array_push($user_ids,  $user_idar);
                 }
            }     
            $set_value["name"]=implode(", ",$user_dataar);
            $set_value["pr_userids"]=$user_ids;

            $set_value["pr_createdby"]=$value['pr_createdby'];
            $set_value["pr_createddate"]=$value['pr_createddate'];
            $set_value["pr_extras"]=$value['pr_extras'];
            $set_value["pr_tag"]=$value['pr_tag'];
             array_push( $prjct_data, $set_value);
        }
      //  dd( $set_value);
        return response()->json([
            'data' => $prjct_data,
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
            'users' => 'required|array',
            'users.*' => 'required|distinct',
            'project_name' => 'required',
            'project_desc' => 'required'
        ]);
        $userid = $request->user_id;
        $username = Admin::where('id',$userid)->get('name');
        $admin = Admin::where('id',1)->get(['id','name']);
        $udet[$admin[0]->id]['userid'] = $admin[0]->id;
        $udet[$admin[0]->id]['emplid'] = $admin[0]->id;
        $udet[$admin[0]->id]['name'] = $admin[0]->name;
        $udet[$admin[0]->id]['role'] = 'admin';
        $users = User::whereIn('id', $validated['users'])->get();
        foreach($users as $user) {
            $udet[$user->id]['userid'] = $user->id;
            $udet[$user->id]['emplid'] = $user->emp_id;
            $udet[$user->id]['name'] = $user->fullname;
            $udet[$user->id]['role'] = 'user';
        }
        $projectroom = new ProjectRoom;
        $projectroom->pr_name = $validated['project_name'];
        $projectroom->pr_description = $validated['project_desc'];
        $projectroom->pr_userids = serialize($udet);
        $projectroom->pr_createdby = $username[0]->name;
        $projectroom->pr_createddate = strtotime('now');
        $result = $projectroom->save();

        $projectroomuser = new ProjectRoomUser;
        $projectroomuser->pr_id = $projectroom->pr_id;
        $projectroomuser->user_id = $userid;
        $projectroomuser->pru_status = 0;
        $projectroomuser->pru_title = $validated['project_name'];
        $projectroomuser->role = 'admin';
        $result1 = $projectroomuser->save();
        foreach($users as $user) {
            $projectroomuser = new ProjectRoomUser;
            $projectroomuser->pr_id = $projectroom->pr_id;
            $projectroomuser->user_id = $user->id;
            $projectroomuser->pru_status = 0;
            $projectroomuser->pru_title = $validated['project_name'];
            $projectroomuser->role = 'user';
            $result1 = $projectroomuser->save();
        }
        if($result && $result1) {
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
        $project = ProjectRoom::find($id);
        $set_value=array();
        $set_value["pr_id"]=$project->pr_id;
        $set_value["pr_name"]=$project->pr_name;
        $set_value["pr_description"]=$project->pr_description;
        $user_data=  unserialize($project->pr_userids);
        $set_value["pr_userids"] = $user_data;
        $set_value["pr_createdby"]=$project->pr_createdby;
        $set_value["pr_createddate"]=$project->pr_createddate;
        $set_value["pr_extras"]=$project->pr_extras;
        $set_value["pr_tag"]=$project->pr_tag;
		if(!$project){
			return response()->json([
				'status' => false,
				'message' => 'Error'
			], 200);
		}
		return response()->json([
			'data' => $set_value,
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
        $validated = $request->validate([
            'users' => 'required|array',
            'users.*' => 'required|distinct',
            'project_name' => 'required',
            'project_desc' => 'required'
        ]);
        $userid = $request->user_id;
        $username = Admin::where('id',$userid)->get('name');
        $admin = Admin::where('id',1)->get(['id','name']);
        $udet[$admin[0]->id]['userid'] = $admin[0]->id;
        $udet[$admin[0]->id]['emplid'] = $admin[0]->id;
        $udet[$admin[0]->id]['name'] = $admin[0]->name;
        $udet[$admin[0]->id]['role'] = 'admin';
        $users = User::whereIn('id', $validated['users'])->get();
        foreach($users as $user) {
            $udet[$user->id]['userid'] = $user->id;
            $udet[$user->id]['emplid'] = $user->emp_id;
            $udet[$user->id]['name'] = $user->fullname;
            $udet[$user->id]['role'] = 'user';
        }
        $projectroom = ProjectRoom::find($id);
        $projectroom->pr_name = $validated['project_name'];
        $projectroom->pr_description = $validated['project_desc'];
        $projectroom->pr_userids = serialize($udet);
        $projectroom->pr_createdby = $username[0]->name;
        $projectroom->pr_createddate = strtotime('now');
        $result = $projectroom->save();

        $prjctrmuser_remove = ProjectRoomUser::where('pr_id',$id)->delete();
        $projectroomuser = new ProjectRoomUser;
        $projectroomuser->pr_id = $id;
        $projectroomuser->user_id = $userid;
        $projectroomuser->pru_status = 0;
        $projectroomuser->pru_title = $validated['project_name'];
        $projectroomuser->role = 'admin';
        $result1 = $projectroomuser->save();
        foreach($users as $user) {
            $projectroomuser = new ProjectRoomUser;
            $projectroomuser->pr_id = $id;
            $projectroomuser->user_id = $user->id;
            $projectroomuser->pru_status = 0;
            $projectroomuser->pru_title = $validated['project_name'];
            $projectroomuser->role = 'user';
            $result1 = $projectroomuser->save();
        }
        if($result && $result1) {
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
    public function destroy($id)
    {
        $result1 = ProjectRoom::where('pr_id',$id)->delete();
        $result2 = ProjectRoomUser::where('pr_id',$id)->delete();
            if($result1 && $result2) {
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
