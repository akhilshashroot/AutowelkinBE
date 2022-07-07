<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\User;
use App\Models\NoticeBoard;
use App\Models\Team;
use App\Models\Department;
use DB;
class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

           	/**
		 * retrieving notice board which creacted for all users
		 */
        //$data= Auth::user();
        $notice = '';
        $result_a=[];
        $color = '';
        $notice_data=array();
        $notice_datas1=array();
        $notice_datas2=array();
        $notice_datas3=array();
        $notice_datas4=array();
        $all = NoticeBoard::select('n.id',DB::raw('"ALL" as user'),'n.notice','notice_board.type as recepient',DB::raw('DATE_FORMAT(n.created, "%d-%m-%Y") as notice_date' ),'notice_board.color as notice_type')
        ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
        ->leftjoin('users as u', 'u.id', '=', 'notice_board.user_id')
        ->leftjoin('team as t', 't.team_id', '=', 'notice_board.team_id')
        ->leftjoin('department as d', 'd.dep_id', '=', 'notice_board.notice_id')
        ->where('notice_board.is_active', 1)
        ->where('notice_board.type', 'all')
        ->groupby('notice_board.notice_id')
        ->get();
        $users_a = [];
        foreach ( $all  as $item) {

            $notice_data['id']=$item->id;
            $notice_data['recepient']=$item->recepient;
            $notice_data['notice']=$item->notice;
            $notice_data['notice_date']=$item->notice_date;
            $notice_data['notice_type']=$item->notice_type;        
            $notice_data['notice_user'] =  "";

       array_push( $notice_datas1, $notice_data);

    }


     /**
      * retrieving notice board which created with team
      */
  
     $team = NoticeBoard::select('n.id',DB::raw('"Team" as user'),'n.notice','notice_board.type as recepient',DB::raw('DATE_FORMAT(n.created, "%d-%m-%Y") as notice_date'),'notice_board.color as notice_type')
     ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
     ->leftjoin('users as u', 'u.id', '=', 'notice_board.user_id')
     ->leftjoin('team as t', 't.team_id', '=', 'notice_board.team_id')
     ->leftjoin('department as d', 'd.dep_id', '=', 'notice_board.notice_id')
     ->where('notice_board.is_active', 1)
     ->groupby('notice_board.notice_id')
     ->where('notice_board.type', 'team')
     ->get();
    

        foreach ( $team  as $item) {
     $users_a = [];

            $notice_data['id']=$item->id;
            $notice_data['recepient']=$item->recepient;
            $notice_data['notice']=$item->notice;
            $notice_data['notice_date']=$item->notice_date;
            $notice_data['notice_type']=$item->notice_type;
            $results = Notice::get_notice_board_team_details($item->id,$item->recepient);
            foreach ($results as $key => $value) {
             if(count($users_a) >0){
                if(!in_array($value->team_id, $users_a[0])){
                    $team =Team::where('team_id',$value->team_id)->first();

                    $teams['id'] = $value->team_id;
                    $teams['name'] = $team->name;
					array_push($users_a,  $teams);
                  
				}

             }else{
                if(!in_array($value->team_id, $users_a)){
                    $team =Team::where('team_id',$value->team_id)->first();

                    $teams['id'] = $value->team_id;
                    $teams['name'] = $team->name;
					array_push($users_a,  $teams);
                  
				}
             }
				
            }
            $notice_data['notice_user'] =  $users_a;

       array_push( $notice_datas2, $notice_data);

    }
     /**
      * retrieving notice board which created with department
      */
      $department = NoticeBoard::select('n.id',DB::raw('"Department" as user'),'n.notice','notice_board.type as recepient',DB::raw('DATE_FORMAT(n.created, "%d-%m-%Y") as notice_date' ) ,'notice_board.color as notice_type')
      ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
      ->leftjoin('users as u', 'u.id', '=', 'notice_board.user_id')
      ->leftjoin('team as t', 't.team_id', '=', 'notice_board.team_id')
      ->leftjoin('department as d', 'd.dep_id', '=', 'notice_board.notice_id')
      ->where('notice_board.type', 'department')
      ->where('notice_board.is_active', 1)
      ->groupby('notice_board.notice_id')
      ->get();
      $users_a = [];

      foreach ( $department  as $item) {

        $notice_data['id']=$item->id;
        $notice_data['recepient']=$item->recepient;
        $notice_data['notice']=$item->notice;
        $notice_data['notice_date']=$item->notice_date;
        $notice_data['notice_type']=$item->notice_type;
        $results = Notice::get_notice_board_department_details($item->id,$item->recepient);
        foreach ($results as $key => $value) {

            if(count($users_a) >0){
                if(!in_array($value->deps_id, $users_a[0])){
                    $team =Department::where('dep_id',$value->deps_id)->first();
    
                    $teams['id'] = $value->deps_id;
                    $teams['name'] = $team->dep_name;
                    array_push($users_a,  $teams);
                  
                }
    
             }else{
                if(!in_array($value->deps_id, $users_a)){
                    $team =Department::where('dep_id',$value->deps_id)->first();
    
                    $teams['id'] = $value->deps_id;
                    $teams['name'] = $team->dep_name;
                    array_push($users_a,  $teams);
                  
                }
             }

           
        }
 
        $notice_data['notice_user'] =  $users_a;

   array_push( $notice_datas3, $notice_data);

}
   
    //  /**
    //   * retrieving notice board with individual users
    //   */
     $individual = NoticeBoard::select('n.id','u.fullname as user','n.notice','notice_board.type as recepient',DB::raw('DATE_FORMAT(n.created, "%d-%m-%Y") as notice_date' ) ,'notice_board.color as notice_type')
       ->leftjoin('notice as n', 'n.id', '=', 'notice_board.notice_id')
     ->leftjoin('users as u', 'u.id', '=', 'notice_board.user_id')
       ->leftjoin('team as t', 't.team_id', '=', 'notice_board.team_id')
     ->leftjoin('department as d', 'd.dep_id', '=', 'notice_board.notice_id')
     ->where('notice_board.type', 'individual')
     ->where('notice_board.is_active', 1)
     ->groupby('notice_board.notice_id')
       ->get();

       foreach ( $individual  as $item) {
        $users_a = [];

        $notice_data['id']=$item->id;
        $notice_data['recepient']=$item->recepient;
        $notice_data['notice']=$item->notice;
        $notice_data['notice_date']=$item->notice_date;
        $notice_data['notice_type']=$item->notice_type;
        $results = Notice::get_notice_board_details($item->id,$item->recepient);
        foreach ($results as $key => $value) {

            if(count($users_a) >0){
                if(!in_array($value->user_id, $users_a[0])){
                    $teamate =User::where('id',$value->user_id)->first();
    
                    $teamss['id'] = $value->user_id;
                    $teamss['name'] = $teamate->fullname;
                    array_push($users_a,  $teamss);
                  
                }
    
             }else{
                if(!in_array($value->user_id, $users_a)){
                    $teamate =User::where('id',$value->user_id)->first();
    
                    $teamss['id'] = $value->user_id;
                    $teamss['name'] = $teamate->fullname;
                    array_push($users_a,  $teamss);
                  
                }
             }
        }
        $notice_data['notice_user'] =  $users_a;

   array_push( $notice_datas4, $notice_data);

}
    $result = array_merge($notice_datas1, $notice_datas2, $notice_datas3, $notice_datas4);
   
       
        return response()->json([
            'data' =>$result,
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
      $notice =$request->notice_text;
      $usertype =$request->notice_usertype;
      $users_a = $request->notice_user;
      $notice_color =$request->notice_color;
       switch ($usertype) {

        case 'individual':

            $insert_a = [];

            $notice_id = Notice::create_notice($notice);

            foreach ($users_a as $key => $value) {

                $row =new \stdClass();;

                $row->user_id = $value;

                $row->notice_id = $notice_id;

                $row->color = $notice_color;

                $row->type = $usertype;

                array_push($insert_a, $row);

            }
            $insert_a =  json_decode( json_encode($insert_a), true);;

           // $result=NoticeBoard::insert([$insert_a]);
           $result=DB::table('notice_board')->insert($insert_a);

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

            break;

        	case 'team':

				$insert_a = [];

                $notice_id = Notice::create_notice($notice);

				$team_users = User::get_users_with_teamids($users_a);

				foreach ($team_users as $key => $value) {

                    $row =new \stdClass();;

					$row->user_id = $value->id;

					$row->notice_id = $notice_id;

					$row->type = $usertype;

					$row->color = $notice_color;

					$row->team_id = $value->team_id;

					$row->deps_id = $value->dep_id;

					array_push($insert_a, $row);

				}
                $insert_a =  json_decode( json_encode($insert_a), true);;

                $result=DB::table('notice_board')->insert($insert_a);

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
				

				break;


                case 'department':

                    $insert_a = [];
    
                    $notice_id = Notice::create_notice($notice);
    
    				$dep_users = User::get_users_with_depIds($users_a);

                    foreach ($dep_users as $key => $value) {
    
                        $row =new \stdClass();;
    
                        $row->user_id = $value->id;
    
                        $row->notice_id = $notice_id;
    
                        $row->type = $usertype;
    
                        $row->color = $notice_color;
    
                        $row->deps_id = $value->dep_id;
    
                        $row->team_id = $value->team_id;
    
                        array_push($insert_a, $row);
    
                    }
    
                    $insert_a =  json_decode( json_encode($insert_a), true);;

                    $result=DB::table('notice_board')->insert($insert_a);
    
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
    
                    break;

                case 'all':

                        $insert_a = [];
        
                        $notice_id = Notice::create_notice($notice);
        
                        $employees =User::getEmployee_daily();
        
                        foreach ($employees as $key => $value) {
        
        
        
                            $row =new \stdClass();;
        
                            $row->user_id = $value['id'];
        
                            $row->notice_id = $notice_id;
        
                            $row->type = $usertype;
        
                            $row->color = $notice_color;
        
                            $row->deps_id = $value['dep_id'];
        
                            $row->team_id = $value['team_id'];
        
                            array_push($insert_a, $row);
        
                        }
        
                        $insert_a =  json_decode( json_encode($insert_a), true);;

                        $result=DB::table('notice_board')->insert($insert_a);
        
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
                        
        
                    break;
        
                    
    
        default:

            # code...

            break;

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
    public function editList($notice_id, $type)
    {
        $type =trim( $type);
        $result = Notice::get_notice_board_details($notice_id, $type);
		$users_a = [];
		$notice = '';
		$result_a=[];
		$color = '';

		

		if($type == 'team'){

			foreach ($result as $key => $value) {

				if(!in_array($value->team_id, $users_a)){

					array_push($users_a, $value->team_id);
                    $team =Team::where('team_id',$value->team_id)->first();
                    $teams['name'] = $team ->name;
					array_push($users_a, $value->team_id);
					array_push($users_a,  $teams);

				}

				$notice = $value->notice;
				$color = $value->color;

			}



			$result_a = ['type' => $type, 'users_a' => $users_a, 'notice' => $notice, 'color' => $color];

		}



		if($type == 'department'){

			foreach ($result as $key => $value) {

				if(!in_array($value->deps_id, $users_a)){

					array_push($users_a, $value->deps_id);

				}

				$notice = $value->notice;
				$color = $value->color;

			}



			$result_a = ['type' => $type, 'users_a' => $users_a, 'notice' => $notice, 'color' => $color];	

		}



		if($type == 'individual'){

			foreach ($result as $key => $value) {

				if(!in_array($value->user_id, $users_a)){
					array_push($users_a, $value->user_id);
				}
				$notice = $value->notice;
				$color = $value->color;
			}
			$result_a = ['type' => $type, 'users_a' => $users_a, 'notice' => $notice, 'color' => $color];
		}
		if($type == 'all'){
			$result_a = ['type' => $type, 'notice' => $result[0]->notice, 'color' => $result[0]->color];
		}
		if($result_a){
            return response()->json([
                'data' =>$result_a,
                'message' => 'Success'
            ], 200);
		}else{
            return response()->json([
                'status' => false,
                'message' => 'Sorry no data found'
            ], 200);
		}

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       $notice =$request->notice_text;
       $usertype =$request->notice_usertype;
       $users_a = $request->notice_user;
       $notice_color =$request->notice_color;
       $update_res =  Notice::update_notice_post($notice, $id);
       if(  $update_res== false ){
        return response()->json([
            'status' => false,
            'message' => 'Error'
        ], 200);
     }
       $deleted_res =  Notice::delete_notice_bord_data($id);
       if($deleted_res == true){
       switch ($usertype) {

        case 'individual':

            $insert_a = [];


            foreach ($users_a as $key => $value) {

                $row =new \stdClass();;

                $row->user_id = $value;

                $row->notice_id = $id;

                $row->color = $notice_color;

                $row->type = $usertype;

                array_push($insert_a, $row);

            }
            $insert_a =  json_decode( json_encode($insert_a), true);;

           // $result=NoticeBoard::insert([$insert_a]);
           $result=DB::table('notice_board')->insert($insert_a);

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

            break;

        	case 'team':

				$insert_a = [];


				$team_users = User::get_users_with_teamids($users_a);

				foreach ($team_users as $key => $value) {

                    $row =new \stdClass();;

					$row->user_id = $value->id;

					$row->notice_id = $id;

					$row->type = $usertype;

					$row->color = $notice_color;

					$row->team_id = $value->team_id;

					$row->deps_id = $value->dep_id;

					array_push($insert_a, $row);

				}
                $insert_a =  json_decode( json_encode($insert_a), true);;

                $result=DB::table('notice_board')->insert($insert_a);

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
				

				break;


                case 'department':

                    $insert_a = [];
    
    
    				$dep_users = User::get_users_with_depIds($users_a);

                    foreach ($dep_users as $key => $value) {
    
                        $row =new \stdClass();;
    
                        $row->user_id = $value->id;
    
                        $row->notice_id = $id;
    
                        $row->type = $usertype;
    
                        $row->color = $notice_color;
    
                        $row->deps_id = $value->dep_id;
    
                        $row->team_id = $value->team_id;
    
                        array_push($insert_a, $row);
    
                    }
    
                    $insert_a =  json_decode( json_encode($insert_a), true);;

                    $result=DB::table('notice_board')->insert($insert_a);
    
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
    
                    break;

                case 'all':

                        $insert_a = [];
        
        
                        $employees =User::getEmployee_daily();
        
                        foreach ($employees as $key => $value) {
        
        
        
                            $row =new \stdClass();;
        
                            $row->user_id = $value['id'];
        
                            $row->notice_id = $id;
        
                            $row->type = $usertype;
        
                            $row->color = $notice_color;
        
                            $row->deps_id = $value['dep_id'];
        
                            $row->team_id = $value['team_id'];
        
                            array_push($insert_a, $row);
        
                        }
        
                        $insert_a =  json_decode( json_encode($insert_a), true);;

                        $result=DB::table('notice_board')->insert($insert_a);
        
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
                        
        
                    break;
        
                    
    
        default:

            # code...

            break;

    }
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

        // $notice_id = $this->input->post('notice_id');

		// $type = $this->input->post('type');
    //    Notice::where('id',$id)->delete();
    //    $updated=NoticeBoard::where('notice_id',$id)->delete(); 
        $updated = NoticeBoard::where('notice_id',$id)->update(['is_active'=>0]);  
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
}

