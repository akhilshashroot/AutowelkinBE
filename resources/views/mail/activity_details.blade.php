<div style="font-family:calibri;">
<br>
<b>
    Employee : {{$datas['employee']}} 
    <br> <br/>
<b>Punch In Time : </b>{{$datas['punchin']}}
<br/><br/>
<b>Punch Out Time : </b>{{$datas['punchout']}}
<br/><br/>
<b>Working Location : </b>{{$datas['work_location']}}<br/><br/>
<b>Punch in IP : </b>{{$datas['punchin_ip']}}<br/><br/>
<b>Punch out IP : </b>{{$datas['punchout_ip']}}<br/><br/>
<b>Total Hours Worked Today:</b> {{$datas['worked']}}<br/><br/>
<b>Total Break Taken : </b>{{$datas['break']}}<br/><br/>
<b>Total Hours Worked : </b>{{$datas['wrking_hrs']}}<br/><br/>
<b>Pending Hours : </b>{{$datas['pending_hrs']}}<br/><br/>
<div class='m-portlet'>
							<div class='m-portlet__head'>
								<div class='m-portlet__head-caption'>
									<div class='m-portlet__head-title'>
										<h3 class='m-portlet__head-text'>
											<u>Daily Checklist </u>
										</h3>
									</div>
								</div>
							</div>
							<div class='m-portlet__body'>
								<!--begin::Section-->
								<div class='m-section'>
									<div class='m-section__content'>
										<table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>
											<thead>
												<tr>
													<th>
                                                    {{"Activity"}}
													</th>
													<th>
                                                    {{"Status"}}
													</th>
													<th>
													{{"Date"}}
													</th>
												</tr>
											</thead>
											<tbody>
                                            @if(count($daily_activity))
                                @foreach($daily_activity as $data)
                                <tr>
                                    <td>
                                        {{$data['activity']}}
                                    </td>
                                    @if($data['status'] == 1)
                                    <td>
                                        {{'Done '}}
                                    </td> 
                                    <td>
                                        {{date('d-m-Y h:i a',$data['time'])}}
                                    </td>
                                    @else
                                    <td>{{'---'}}</td>
                                    <td> {{'---'}}</td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr colspan="4"><td>{{'No Daily Checklists are assigned'}}</td></tr>
                                @endif
                                </tr></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


<br/><br/>
<div class='m-portlet'>
							<div class='m-portlet__head'>
								<div class='m-portlet__head-caption'>
									<div class='m-portlet__head-title'>
										<h3 class='m-portlet__head-text'>
											<u>Daily Report</u>
										</h3>
									</div>
								</div>
							</div>
							<div class='m-portlet__body'>
								<!--begin::Section-->
								<div class='m-section'>
									<div class='m-section__content'>
										<table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>
											<thead>
												<tr>
													<th>
														{{"Activity"}}
													</th>
													<th>
														{{"Status"}}
													</th>
													<th>
														{{"Date & Time"}}
													</th>
												</tr>
											</thead>
											<tbody>
                                            @if(count($daily_activity_list))
                                @foreach($daily_activity_list as $data)
                                <tr>
                                    <td>
                                        {{$data['activity']}}
                                    </td>
                                    @if($data['status'] == 1)
                                    <td>
                                        {{$data['reply']}}
                                    </td>
                                    <td>
                                        {{'Done '}}
                                    </td> 
                                    @else
                                    <td>{{'---'}}</td>
                                    <td> {{'---'}}</td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr colspan="3"><td>{{'No Daily Report Found'}}</td></tr>
                                @endif
                                </tbody>

					</table>

				</div>

			</div>



		</div>



	</div>

<br/><br/>
<div class='m-portlet'>

							<div class='m-portlet__head'>

								<div class='m-portlet__head-caption'>

									<div class='m-portlet__head-title'>

										<h3 class='m-portlet__head-text'>

											<u> Weekly Checklist </u>

										</h3>

									</div>

								</div>

							</div>

							<div class='m-portlet__body'>
                            <table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>

<thead>

    <tr>

        

        <th>

            {{"Task"}}

        </th>

        <th>

            {{"Time"}}

        </th>

        <th>

            {{"Status"}}

        </th>

    </tr>

</thead><tbody>
@if(count($weekly_checklist) || count($fullWeeklyChecklist))
@if(count($weekly_checklist))
                                @foreach($weekly_checklist as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{date('d-m-Y',$data['wd_date'])}}
                                    </td>
                                    <td>
                                        {{'Done'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(count($fullWeeklyChecklist))
                                @foreach($fullWeeklyChecklist as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{'---'}}
                                    </td>
                                    <td>
                                        {{'---'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @else
                    <tr><td>
                            <table><tr colspan="3"><td>{{'No weekly Checklists are assigned'}}</td></tr></table></td></tr>
                    @endif
                    </tbody></table></div></div>
<br/><br/>
<div class='m-portlet'>

							<div class='m-portlet__head'>

								<div class='m-portlet__head-caption'>

									<div class='m-portlet__head-title'>

										<h3 class='m-portlet__head-text'>

											 <u>Weekly work Report</u>

										</h3>

									</div>

								</div>

							</div>

							<div class='m-portlet__body'>
                            <table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>

<thead>

    <tr>

        <th>

            {{"Task"}}

        </th>

        <th>

            {{"Time"}}

        </th>

        <th>

        {{"Report"}}	

        </th>

    </tr>

</thead><tbody>
@if(count($weekly_workreport) || count($full_weekly_workreport))
                   
                                @if(count($weekly_workreport))
                                @foreach($weekly_workreport as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{date('d-m-Y',$data['wd_date'])}}
                                    </td>
                                    <td>
                                    {{$data['wd_status']}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(count($full_weekly_workreport))
                                @foreach($full_weekly_workreport as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{'---'}}
                                    </td>
                                    <td>
                                        {{'---'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                    @else
                    <tr><td>
                            <table><tr colspan="3"><td>{{'No weekly work reports found'}}</td></tr></table></td></tr>
                    @endif
                    </tbody></table></div></div>

<br/><br/>
<div class='m-portlet'>

							<div class='m-portlet__head'>

								<div class='m-portlet__head-caption'>

									<div class='m-portlet__head-title'>

										<h3 class='m-portlet__head-text'>

											<u>Monthly Checklist</u>

										</h3>

									</div>

								</div>

							</div>

							<div class='m-portlet__body'>
                            <table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>

<thead>

    <tr>

        <th>

            {{"Task"}} 

        </th>

        <th>

            {{"Time"}}

        </th>

        <th>

            {{"Status"}}

        </th>

    </tr>

</thead><tbody>
@if(count($monthly_checklist) || count($full_monthly_checklist))
@if(count($monthly_checklist))
                                @foreach($monthly_checklist as $data)
                                <tr>
                                    <td>
                                        {{$data['ma_activity']}}
                                    </td>
                                    <td>
                                    {{date('d-m-Y',$data['md_date'])}}
                                    </td>
                                    <td>
                                    {{'Done'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(count($full_monthly_checklist))
                                @foreach($full_monthly_checklist as $data)
                                <tr>
                                    <td>
                                        {{$data['ma_activity']}}
                                    </td>
                                    <td>
                                    {{'---'}}
                                    </td>
                                    <td>
                                        {{'---'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @else
                    <tr><td>
                            <table><tr colspan="3"><td>{{'No monthly checklist found'}}</td></tr></table></td></tr>
                    @endif
                                </tbody></table></div></div>
<br/><br/>
<div class='m-portlet'>

							<div class='m-portlet__head'>

								<div class='m-portlet__head-caption'>

									<div class='m-portlet__head-title'>

										<h3 class='m-portlet__head-text'>

											 <u>Monthly work Report</u>

										</h3>

									</div>

								</div>

							</div>

							<div class='m-portlet__body'>
                            <table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>

<thead>

    <tr>

        <th>

            {{"Task"}}

        </th>

        <th>

            {{"Time"}}

        </th>

        <th>

        {{"Report"}}	

        </th>

    </tr>

</thead><tbody>
@if(count($monthly_workreport_act) || count($full_monthly_workreport_act))
@if(count($monthly_workreport_act))
                                @foreach($monthly_workreport_act as $data)
                                <tr>
                                    <td>
                                        {{$data['ma_activity']}}
                                    </td>
                                    <td>
                                    {{date('d-m-Y',$data['md_date'])}}
                                    </td>
                                    <td>
                                    {{$data['md_status']}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(count($full_monthly_workreport_act))
                                @foreach($full_monthly_workreport_act as $data)
                                <tr>
                                    <td>
                                        {{$data['ma_activity']}}
                                    </td>
                                    <td>
                                    {{'---'}}
                                    </td>
                                    <td>
                                        {{'---'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @else
                    <tr><td>
                            <table><tr colspan="3"><td>{{'No monthly work reports found'}}</td></tr></table></td></tr>
                    @endif
                                </tbody></table></div></div>
<br/><br/>
<div class='m-portlet'>
								<div class='m-portlet__head'>
									<div class='m-portlet__head-caption'>
										<div class='m-portlet__head-title'>
											<h3 class='m-portlet__head-text'>
												<u>Details of Tickets Worked </u>
											</h3>
										</div>
									</div>
								</div>
                                <div class='m-portlet__body'>
									<!--begin::Section-->
									<div class='m-section'>
										<div class='m-section__content'>
											<table class='table table-striped m-table' border='1' style='border-collapse:collapse;'>
												<thead>
													<tr>
														<th class='col-md-3'>
															{{"Ticket Url"}}
														</th>
														<th class='col-md-9'>
															{{"Ticket Response"}}
														</th>
														<th class='col-md-9'>
															{{"Response Time"}}
														</th>
													</tr>
												</thead><tbody>
                                                @if(count($ticket_details))
                                @foreach($ticket_details as $data)
                                <tr>
                                    <td>
                                        {{$data['ticket_id']}}
                                    </td>
                                    <td>
                                    {{$data['response']}}
                                    </td>
                                    <td>
                                    {{$data['sla']}}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr colspan="3"><td>{{'No Tickets Found'}}</td></tr>
                                @endif
</div>