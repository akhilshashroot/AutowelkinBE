<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requst;
use App\Models\Performance;
use App\Models\Assignment;
use App\Models\WeeklyWorkingHour;
use App\Models\SettingHour;
use App\Models\AttendanceLog;
use App\Models\PerformanceHistory;

class ScoreController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$user_id)
    {
        $user_id =$user_id;
        $performance = User::select('performance.*')->join('performance', 'performance.user_id','=','users.id')
        ->orderby("performance.performance_id", "desc")
        ->where('id',$user_id)->first();

        $sumtotal_pe  = Performance::total_pe($performance->performance_id); 
        $sumtotal_ce  = Performance::total_ce( $performance->performance_id); 
        $sumtotal_ie  = Performance::total_ie( $performance->performance_id); 
        $performance['total_performance_score'] = $sumtotal_pe[0]['total_pe'];
		$performance['total_cultural_score'] = $sumtotal_ce[0]['total_ce'];
		$performance['total_integrity_score'] = $sumtotal_ie[0]['total_ie'];
        return response()->json([
            'data' => $performance,
            'message' => 'Success'
        ], 200);
      //  dd($performance);
      
    }

    //check field array
    Public function get_array_field($field){
        $fields = array("Extra Curricular Activities", "Awards", "Suspensions", "Warnings","Client Policy Violation","Client Reviews");
        $field_array = array("Extra Curricular Activities"=>"Extracurricular Activitiess","Awards"=>"Awards & Achievements","Suspensions"=>"Suspension","Warnings"=>"Warning","Client Policy Violation"=>"Policy Violation","Client Reviews"=>"Client Review");

        if (in_array($field, $fields)){
           return  $field_array[$field];
        }
        return  $field;


    }

    /**
 * Score details 
 */
public function get_evaluation_details(Request $request){

	$performance_id = $request->performance_id;

	$field = $request->field;
    $field = $this->get_array_field($field );
	$user_id =$request->user_id;
    $result = PerformanceHistory::pe_history($user_id);	
    	$output_a = [];

	foreach ($result as $value) {

		if(strtolower($value->criteria) == strtolower($field)){



			$insert_a = new \stdClass();

			$insert_a->score = $value->point;

			$insert_a->date = date('Y-m-d',$value->time);

			$insert_a->comment = $value->comments;

			array_push($output_a, $insert_a);
			

		}

	}


	if(count($output_a)>0){
        return response()->json([
            'data' => $output_a,
            'message' => 'Success'
        ], 200);

	}else{
        return response()->json([
            'data' => $output_a,
            'message' => 'Sorry no data available.'
        ], 200);

	}

}
    /**
     * get_evaluation_history 
     */
    Public function get_evaluation_history(Request $request){
        
        $user_id  = $request->user_id;
        $history     = array();
        $re          =  User::select('performance.*')->join('performance', 'performance.user_id','=','users.id')
                            ->orderby("performance.performance_id", "desc")
                            ->where('id',$user_id)->first();
                        
    //			fetching current month pe details
        $month_id    =  date("m-Y", strtotime($request->month_pick));
     //   $newDate = date("m-Y", strtotime($request->month_pick));
        if($month_id ==''){
            $month_id =date('m-Y');
        }
        $m           = strtotime("01-".$month_id);
        $history_ids = PerformanceHistory::history_ids($month_id,$re->user_id);
        $cri_pe='';
        $cri_ce='';
        $sm_pe = 0;
        $sm_ce = 0;
        $sm_ie  = 0;
        $month_name  =  date('F Y',$m);
        $array_data['pe_data']=array();
        $array_data['ce_data']=array();
        $array_data['ie_data']=array();

        if(count($history_ids)>0){
    //				$test2 = strtotime('first day of this month 00:00:00');
            foreach($history_ids as $hids){
            $pe_datas = PerformanceHistory::history($hids['performance_id'],$month_id);
   // dd( $pe_datas);
    if(count($pe_datas)>0){
        foreach($pe_datas as $row){

            if($row['cri_type']==1){

                if($row['point']>=0){
                
                    $sm_pe = $sm_pe +$row['point'];
                    $array_coms['pe_criteria']=$row['criteria'];
                    $array_coms['pe_point']= $row['point'];
                    $array_coms['pe_date']=date('d-m-Y',$row['time']);
                    $array_data['pe_sum']=  $sm_pe;   
                    array_push( $array_data['pe_data'], $array_coms);

                    //	close test
                }else{
                    $sm_pe = $sm_pe +$row['point'];
                    $array_coms['pe_criteria']=$row['criteria'];
                    $array_coms['pe_point']= $row['point'];
                    $array_coms['pe_date']=date('d-m-Y',$row['time']);
                    $array_data['pe_sum']=  $sm_pe;   
                    array_push( $array_data['pe_data'], $array_coms);
                }
            }elseif($row['cri_type']==2){

                if($row['point']>=0){

                    $sm_ie   = $sm_ie +$row['point'];
                    $array_com2['ie_criteria']=$row['criteria'];
                    $array_com2['ie_point']= $row['point'];
                    $array_com2['ie_date']=date('d-m-Y',$row['time']);
                    $array_data['ie_sum']=  $sm_ie;   
                    array_push( $array_data['ie_data'], $array_com2);
                }else{
                    $sm_ie   = $sm_ie +$row['point'];
                    $array_com2['ie_criteria']=$row['criteria'];
                    $array_com2['ie_point']= $row['point'];
                    $array_com2['ie_date']=date('d-m-Y',$row['time']);
                    $array_data['ie_sum']=  $sm_ie;   
                    array_push( $array_data['ie_data'], $array_com2);
                }
            }elseif($row['cri_type']==3){
                if($row['point']>=0){
                    $sm_ce   = $sm_ce +$row['point'];
                    $array_com['ce_criteria']=$row['criteria'];
                    $array_com['ce_point']= $row['point'];
                    $array_com['ce_date']=date('d-m-Y',$row['time']);
                    $array_data['ce_sum']=  $sm_ce;   
                    array_push( $array_data['ce_data'], $array_com);
                }else{
                    $sm_ce   = $sm_ce +$row['point'];
                    $array_com['ce_criteria']=$row['criteria'];
                    $array_com['ce_point']= $row['point'];
                    $array_com['ce_date']=date('d-m-Y',$row['time']);
                    $array_data['ce_sum']=  $sm_ce;   
                    array_push( $array_data['ce_data'], $array_com);
                }

            }   
        }
    }
    }


    }

    $comments = PerformanceHistory::getting_comments($user_id,$month_id);
    $array_data['comments']=array();
    $array_empty=[];
        $sl =1;
        foreach($comments as $coms){
                $array_com1['id']=$sl;
                $array_com1['comments']=$coms['comments'];
                $array_com1['date']=date('d-m-Y',$coms['time']);
            $sl++; 
            array_push( $array_data['comments'], $array_com1);

        }
            return response()->json([
                'status' => true,
                'data' => $array_data,
                'message' => 'Success'
            ], 200);
                            
    }  
}
