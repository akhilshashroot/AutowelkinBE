        Hi , <br/>
        <br/> {{$user_data->fullname}}  ({{$user_data->email}}) has requested {{$rqtype}}.<br> <br />
        @if(!empty($request_data['lv_date_to']) && $request_data['lv_date_to']!= $request_data['lv_date'] )

		<span class="float-left"> <b>From Date: </b>{{date('d M Y',$request_data['lv_date'])}} <b>- To  Date: </b>{{date('d M Y', $request_data['lv_date_to'])}}</span>

		@else
			<span class="float-left"> <b>Date: </b>{{date('d M Y',$request_data['lv_date'])}}</span>
		@endif     <br/>   <br />
        <b>Consent of : </b>{{$request_data['approvedby']}}
      <br/>
      <p><b>Reason : </b>{{$request_data['lv_purpose']}}</p> 
      