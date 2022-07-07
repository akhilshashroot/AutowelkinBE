<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use App\Models\Department;
use Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::select('dep_id','dep_name')->orderBy('dep_id','desc')->get();

        return response()->json([
            'data' => $departments,
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
        $validator = Validator::make($request->all(), [
            'dep_name' => 'required|unique:department',
         ]);
        if($validator->fails()){
            return response()->json(['status' => false,$validator->messages()], 200);
        } else {
            $department = new Department;
            $department->dep_name = $request->dep_name;
            $department->job_desc = '';
            $result = $department->save();
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
        $validator = Validator::make($request->all(), [
            'dep_name' => 'required|unique:department,dep_name,'.$id.',dep_id'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false,$validator->messages()], 200);
        } else {
        $department = Department::find($id);
        if(!$department){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $department->dep_name =$request->dep_name;
        $department->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
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
        //$user = Auth::user();
        //if(isset($request->role)) {
            //if($request->role != 4) {
                $result = Department::where('dep_id',$id)->delete();
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
            /*}else{
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to delete!'
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'role is required'
            ], 200);
        }*/
    }
}
