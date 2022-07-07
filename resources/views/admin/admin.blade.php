@extends('layouts.admin_main')
@section('title')
        <title>
            HashRoot One |  {{$data->name}}
        </title>
@endsection
@section('subheader')
<div class="m-subheader ">
							<div class="d-flex align-items-center">
								<div>
									<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
        <span class="m-subheader__daterange-label">
            <span class="m-subheader__daterange-title">Hello, </span>
            <span class="m-subheader__daterange-date m--font-brand">{{$data->name}}</span>
        </span>
        <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
            <i class="la la-user"></i>
        </a>
		</span>
								</div>
							</div>
						</div>
@endsection
@section('main_content')
<!--begin:: Widgets/Stats-->
<div class="m-portlet">
								<div class="m-portlet__body  m-portlet__body--no-padding">
									<div class="row m-row--no-padding m-row--col-separator-xl">
                                        @if($data->role!=5)
                                            <div class="col-md-12 col-lg-6 col-xl-3">
                                                <!--begin::Total Profit-->
                                                <div class="m-widget24">
                                                    <div class="m-widget24__item">
                                                        <h4 class="m-widget24__title">
                                                            Employees
                                                        </h4>
                                                        <br>
                                                        <span class="m-widget24__desc">
                                                            Employee List
                                                        </span>
                                                        <a href="{{route('admin.userlist')}}" class="m-widget24__stats m--font-brand" style="text-decoration: none;">
                                                            <i style="font-size:3.3rem;" class="flaticon-users"></i>
                                                        </a>
                                                        <span class="m-widget24__stats m--font-brand">
                                                        
                                                        </span>
                                                        <div class="m--space-10"></div>
                                                        <div class="progress m-progress--sm">
                                                            <div class="progress-bar m--bg-brand" role="progressbar" style="width: 78%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        
                                                                <a href="{{route('admin.userlist')}}" class="m-widget24__change">
                                                                View 
                                                            </a>
                                                        

                                                    </div>
                                                </div>
                                                <!--end::Total Profit-->
                                            </div>
                                            
                                            
                                            <div class="col-md-12 col-lg-6 col-xl-3">
                                                <!--begin::New Feedbacks-->
                                                <div class="m-widget24">
                                                    <div class="m-widget24__item">
                                                        <h4 class="m-widget24__title">
                                                            Department
                                                        </h4>
                                                        
                                                        <br>
                                                        <span class="m-widget24__desc">
                                                            Dept. Info
                                                        </span>
                                                        
                                                        <a href="javascript:;" class="m-widget24__stats m--font-info"  onclick="viewalldept()" style="text-decoration: none;">
                                                            <i style="font-size:3.3rem;" class="flaticon-interface-4"></i>
                                                        </a>
                                                        <div class="m--space-10"></div>
                                                        <div class="progress m-progress--sm">
                                                            <div class="progress-bar m--bg-info" role="progressbar" style="width: 84%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <a href="javascript:;" class="m-widget24__change" onclick="viewalldept()">
                                                            View
                                                        </a>
                                                        <a href="#newdept" class="m-widget24__change" data-toggle="modal" data-target="#newdept">
                                                            Add
                                                        </a>
                                                        
                                                    </div>
                                                </div>
                                                <!--end::New Feedbacks-->
                                            </div>
                                        @endif
                                        @if($data->role!=4)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Orders-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Team
													</h4>
													<br>
													<span class="m-widget24__desc">
														Team Info
													</span>
													<a href="javascript:;" class="m-widget24__stats m--font-danger" onclick="viewallteams()" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-suitcase"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-danger" role="progressbar" style="width: 69%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="javascript:;" class="m-widget24__change" onclick="viewallteams()" class="m-widget24__change" data-toggle="modal" data-target="#viewteam" >
														View
													</a>
													<a href="#newteam" class="m-widget24__change" data-toggle="modal" data-target="#newteam">
														Add
													</a>
													
												</div>
											</div>
											<!--end::New Orders-->
										</div>
                                        @endif
										<!-- Attendance -->
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::Total Profit-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Attendance
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														 Atendance
													</span>
													
													<a href="javascript:;" class="m-widget24__stats m--font-success"  onclick="viewalldept()" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class=" flaticon-event-calendar-symbol"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-success" role="progressbar" style="width: 84%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
												
<!--
													<a href="javascript:;" class="m-widget24__change" onclick="viewalldepts()">
														Add New Activity
													</a>
-->
													<a href="{{route('attendance.index')}}" class="m-widget24__change">View</a>
											<!--		<a href="#" class="m-widget24__change">Assigned</a>
													<a href="#" class="m-widget24__change">Tickets</a>-->
<!--
													<a href="javascript:;" class="m-widget24__change" onclick="view_status()">
														View
													</a>
-->
			
												</div>
											</div>
											<!--end::Total Profit-->
										</div>
										
									</div>
									
									
									<div class="row m-row--no-padding m-row--col-separator-xl">
									   
							<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::Total Profit-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Requests
													</h4>
													<br> 
													<span class="m-widget24__desc">
														Requests Info
													</span>
													<a href="admin/request" class="m-widget24__stats m--font-focus" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-file"></i>
													</a>
													<span class="m-widget24__stats m--font-focus"></span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-focus" role="progressbar" style="width: 78%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													
														<a href="admin/request"
														 class="m-widget24__change">
															View 
														</a>
													
<!--
													<span class="m-widget24__number">
														78%
													</span>
-->
												</div>
											</div>
											<!--end::Total Profit-->
										</div>
										
										
										
										
							<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Daily Reports
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														Dept. Info
													</span>
													
													<a href="javascript:;" class="m-widget24__stats m--font-accent"  onclick="viewalldept()" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-list-2"></i>
													</a>
													<span class="m-widget24__stats m--font-focus"></span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-accent" role="progressbar" style="width: 54%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="./view_daily_stat" class="m-widget24__change">View</a>
													<a href="javascript:;" class="m-widget24__change" onclick="viewalldepts_jd()">
														Assign  
													</a>
													<a href="./Daily_history" class="m-widget24__change" >
														History
													</a> 

												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
							<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::Total Profit-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Weekly Reports 
													</h4>
													<br>
													<span class="m-widget24__desc">
														Weekly Reports Info
													</span>
													<a href="admin/request" class="m-widget24__stats m--font-warning" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-list-2 "></i>
													</a>
													<span class="m-widget24__stats m--font-warning">
													
													</span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-warning" role="progressbar" style="width: 78%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													
															
															<a href="./view_weekly_stat" class="m-widget24__change">View</a>
															<a href="javascript:;" onclick="weeklyactivity()" style="    margin-left: 1.2rem;" class="m-widget24__change">Assign</a>
<!--
													<span class="m-widget24__number">
														78%
													</span>
-->
												</div>
											</div>
											<!--end::Total Profit-->
										</div>	
							<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Monthly Reports 
													</h4>
													<br>
													<span class="m-widget24__desc">
														Monthly Reports Info
													</span>
													<a href="admin/request" class="m-widget24__stats m--font-brand" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-list-2 "></i>
													</a>
													<span class="m-widget24__stats m--font-brand">
													
													</span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-brand" role="progressbar" style="width: 78%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													
															<a href="./view_monthly_stat"  class="m-widget24__change">View</a>
															<a href="javascript:;" onclick="monthlyactivity()" style="    margin-left: 1.2rem;" class="m-widget24__change">Assign</a>
															
<!--
													<span class="m-widget24__number">
														78%
													</span>
-->
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
							<!--Close new view tab-->
									</div>
									
									<div class="row m-row--no-padding m-row--col-separator-xl">
									    @if( $data->role != 1 && $data->role != 5 && $data->role != 4 )
                                        <div class="col-md-12 col-lg-6 col-xl-3">
										  <!--begin::New Users-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Inventory Management
													</h4>
													<br>
													<span class="m-widget24__desc">
														Manage Inventory
													</span>
													<a href="#" style="text-decoration: none;" class="m-widget24__stats m--font-warning">
														<i style="font-size:3.3rem;" class="flaticon-tabs"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-warning" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="../inventory/view_inv" class="m-widget24__change">View</a>
													<a href="#newinv" class="m-widget24__change" data-toggle="modal" data-target="#newinv" onclick="selectTeams()">Add</a>

												</div>
											</div>
											
											<!--end::New Users-->
											
										</div>
                                        @endif
                                        @if($data->role!=5 && $data->role!=4)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
										  <!--begin::New Users-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Notification Management
													</h4>
													<br>
													<span class="m-widget24__desc">
														Manage LinkedIn
													</span>
													<a href="#" style="text-decoration: none;" class="m-widget24__stats m--font-brand">
														<i style="font-size:3.3rem;" class="flaticon-list-1"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-brand" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="javascript:;" onclick="viewlinkedinnot()" class="m-widget24__change">View List</a>
													

												</div>
											</div>
											
											<!--end::New Users-->
											
										</div>
                                        @endif
                                        @if($data->role!=5)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
										  <!--begin::New Users-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Project Management
													</h4>
													<br>
													<span class="m-widget24__desc">
														Manage Projects 
													</span>
													<a href="#" style="text-decoration: none;" class="m-widget24__stats m--font-warning">
														<i style="font-size:3.3rem;" class="flaticon-tabs"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-warning" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<!--<a href="../inventory/view_inv"  style="margin-left: 1.2rem;" class="m-widget24__change">View</a>--> 
													<a href="#newprojectroom" class="m-widget24__change" data-toggle="modal" >Add</a>
													<a href="view_projectrooms" class="m-widget24__change"  target = "_blank">View</a>  
													<a href="admin_chat" class="m-widget24__change"  target = "_blank">Chat</a>  

												</div>
											</div>
											
											<!--end::New Users-->
											
										</div>
                                        @endif
                                        @if($data->role!=1&&$data->role!=2&&$data->role!=3&&$data->role!=5)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
										  <!--begin::New Users-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Settings
													</h4>
													<br>
													<span class="m-widget24__desc">
														Admin Settings
													</span>
													<a href="#" style="text-decoration: none;" class="m-widget24__stats m--font-success">
														<i style="font-size:3.3rem;" class="flaticon-settings"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-success" role="progressbar" style="width: 90%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="javascript:;" class="m-widget24__change" onclick="adminsettings()">
														Change
													</a>

												</div>
											</div>
											<!--end::New Users-->
											
										</div>
                                        @endif
									</div>
									<div class="row m-row--no-padding m-row--col-separator-xl">
                                        @if($data->role!=4 && $data->role!=5)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Shift Records
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														View Team Shifts
													</span>
													
													<a href="javascript:;" class="m-widget24__stats m--font-accent"  onclick="viewalldept()" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-list-2"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-accent" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a target="_blank" href="./view_shifts"  class="m-widget24__change">View</a>
													
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
                                        @endif
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Interview Scheduler
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														Create Interview
													</span>
													
													<a href="#exam_model"  data-toggle="modal" class="m-widget24__stats m--font-danger" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-list-2"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-danger" role="progressbar" style="width: 84%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="#exam_model"  class="m-widget24__change" data-toggle="modal">Create</a>
												
													<!-- <a href="#exam_list_model" onclick="search_interview(0)"  style="    margin-left: 1.2rem;" class="m-widget24__change" data-toggle="modal">View Candidates</a> -->
													<a href="interview_list"   style=" margin-left: 1.2rem;" class="m-widget24__change">View Candidates</a>
												
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
                                        @if($data->role!=4&&$data->role!=5)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Announcements
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														Create & view Announcement
													</span>
													
													<a href="#notice_board_modal"  data-toggle="modal" class="m-widget24__stats m--font-info" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-notes"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-info" role="progressbar" style="width: 84%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="#notice_board_modal" class="m-widget24__change" data-toggle="modal">Create</a>
													<a href="javascript:void(0)" onclick="get_notice_board_list()"  style="    margin-left: 1.2rem;" class="m-widget24__change" data-toggle="modal">View</a>
													
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
                                        @endif
                                        @if($data->role!=5)
                                        <div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Hashbook
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														Create & view Discussion
													</span>
													
													<a href="discussion/dashboard/" target="_blank" class="m-widget24__stats m--font-focus" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-chat-1"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-focus" role="progressbar" style="width: 64%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a href="discussion/dashboard/" class="m-widget24__change" target="_blank" >View</a>
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>

									</div>

									<div class="row m-row--no-padding m-row--col-separator-xl">
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Designation
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														Designation Info
													</span>
													
													<a href="#newdesignation_modal"  data-toggle="modal" class="m-widget24__stats m--font-focus" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-suitcase"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-focus" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a  href="#newdesignation_modal"  data-toggle="modal"  class="m-widget24__change">Create</a>
													<a  href="javascript:void(0)" onclick="view_designations()"  data-toggle="modal" style="margin-left: 1.2rem;" class="m-widget24__change">View</a>
													
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>

										<!-- Tasker -->
									
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Tasker
													</h4>
													
													<br>
													<span class="m-widget24__desc">
														All about tasks
													</span>
													
													<a  href="/Admin/taskManagement"  target="_blank"  class="m-widget24__stats m--font-success" style="text-decoration: none;">
														<i style="font-size:3.3rem;" class="flaticon-list-3"></i>
													</a>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<a  href="/Admin/taskManagement"  target="_blank" class="m-widget24__change">Control Panel</a>
													
													
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
										
										<!-- Tasker Ends -->
										</div>
                                        @endif
								</div>
							</div>
							<!--end:: Widgets/Stats-->
@endsection
@section('modaldiv')
@include('admin.department')
@include('admin.team')
@endsection