<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Log;

class WorkReport extends Model
{
    use HasFactory;
    protected $table = 'workreport';
    protected $primaryKey = 'workreport_id';
    public $timestamps = false;



	Public static function getTicketsCount($dep_id,$user_id ){
		$data		= AttendanceLog::workReportGraph($user_id);
		$result['resolved'] =0;
		$result['pending'] =0;
		$result['handled'] =0;
		$result['sla'] =0;			

		foreach ($data as $index => $report) {
			$tickets			 	   = unserialize($report->work_report); 
		//	dd($tickets[600]['reply']);
			if($dep_id==2){
				// $result['resolved']    = $result['resolved']+$tickets[600]['reply'];
				// $result['pending']     = $result['pending']+$tickets[601]['reply'];
				// $result['handled']     = $result['handled']+$tickets[599]['reply'];
			// 	$result['resolved']    = $result['resolved']+isset($tickets[600]['reply'])? $result['resolved']+(int)$tickets[600]['reply']:0;
			// 	$result['pending']     = $result['pending']+isset($tickets[601]['reply'])?$result['pending']+(int)$tickets[601]['reply']:0;
			// Log::info($result['pending'] );
			// 	$result['handled']     = $result['handled']+isset($tickets[599]['reply'])?$result['handled']+(int)$tickets[599]['reply']:0;
				$result['sla']     		  = $result['sla']+$report->sla_violation;

				$resultp= isset($tickets[601]['reply'])?(int)$tickets[601]['reply']:0;
				$result['pending']    = 	$result['pending']+$resultp;
				$resultr= isset($tickets[600]['reply'])?(int)$tickets[600]['reply']:0;
				$result['resolved']    = 	$result['resolved']+$resultr;
				$resulth= isset($tickets[599]['reply'])?(int)$tickets[599]['reply']:0;
				$result['handled']    = 	$result['handled']+$resulth;
			}

			if($dep_id==51){
				// $result['resolved']    = $result['resolved']+$tickets[701]['reply'];
				// $result['pending']     = $result['pending']+$tickets[702]['reply'];
				// $result['handled']     = $result['handled']+$tickets[703]['reply'];
				$result['resolved']    = $result['resolved']+isset($tickets[701]['reply'])? $result['resolved']+(int)$tickets[701]['reply']:0;
				$result['pending']     = $result['pending']+isset($tickets[702]['reply'])?$result['pending']+(int)$tickets[702]['reply']:0;
				$result['handled']     = $result['handled']+isset($tickets[703]['reply'])?$result['handled']+(int)$tickets[703]['reply']:0;

				$result['sla']     		  = $result['sla']+$report->sla_violation;
			}
			if($dep_id==52){
				$resultp= isset($tickets[710]['reply'])?(int)$tickets[710]['reply']:0;
				$result['pending']    = 	$result['pending']+$resultp;
				$resultr= isset($tickets[709]['reply'])?(int)$tickets[709]['reply']:0;
				$result['resolved']    = 	$result['resolved']+$resultr;
				$resulth= isset($tickets[711]['reply'])?(int)$tickets[711]['reply']:0;
				$result['handled']    = 	$result['handled']+$resulth;

				// $result['pending']     = $result['pending']+$tickets[710]['reply']?? 0;
				// $result['handled']     = $result['handled']+$tickets[711]['reply']??0;
				$result['sla']     		  = $result['sla']+$report->sla_violation;
			}

		}
		if($user_id==687){
		$result['resolved']=$result['resolved']+15;
       	}
		return $result; 
	}

	

}
