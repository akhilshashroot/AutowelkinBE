<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Inventory;
use Validator;

class TeamController extends Controller
{
      /**
     * show team members.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $result = Team::select('team_id','name','mail_ids')->orderBy('team_id','desc')->get();

        return response()->json([
            'data' =>  $result ,
            'message' => 'Success'
        ], 200);
    }

          /**
     * store team members.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|unique:team,name',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false,$validator->messages()], 200);
        } else {
            $team= Team::create([
                'name' => $request->team_name,
             ]);
             if($team) {
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
     * edit team members.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|unique:team,name,'.$id.',team_id'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false,$validator->messages()], 200);
        } else {
            $team = Team::find($id);
            if(!$team){
                return response()->json([
                    'status' => false,
                    'message' => 'Error'
                ], 200);
            }
            $team->name = $request->team_name;
            $team->mail_ids = $request->mail_id;
            $team->save();              
    
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        }
    }

          /**
     * delete team members.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $team = Team::find($id);

        if(auth()->user()->role !=4 && $team){
            $inventery=Inventory::where('inv_team',$id)->get();
             if(count( $inventery)>0){

                Inventory::where('inv_team',$id)->update(['inv_team' => 5]);
                $users=  User::where('team_id',$id)->get(); 
                if(count( $users)>0){
                    User::where('team_id',$id)->update(['team_id' => 5]);

                }        
             }
         $team->delete();
         return response()->json([
            'status' => true,
            'message' => 'Success'
        ], 200);
		}else{
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);	
        }
      
     }
}
