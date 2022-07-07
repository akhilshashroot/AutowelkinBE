<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Designation;
use Validator;

class DesignationController extends Controller
{
    public function index()
    {
        $designation = Designation::select('desg_id','designation')->orderBy('desg_id','desc')->get();

        return response()->json([
            'data' => $designation,
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
            'designation' => 'required|unique:designation',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false,$validator->messages()], 200);
        } else {
            $designation = new Designation;
            $designation->designation = $request->designation;
            $result = $designation->save();
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
            'designation' => 'required|unique:designation,designation,'.$id.',desg_id'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false,$validator->messages()], 200);
        } else {
            $validated = $request->validate([
                'designation' => 'required',
            ]);
            $designation = Designation::find($id);
            if(!$designation){
                return response()->json([
                    'status' => false,
                    'message' => 'Error'
                ], 200);
            }
            $designation->designation =$request->designation;
            $designation->save();   
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
            $result = Designation::where('desg_id',$id)->delete();
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
