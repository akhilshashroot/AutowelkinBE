<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
		Autowelkin One | Employees
		</title>
		<meta name="description" content="User profile view and edit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<!--end::Web font -->
		<!--begin::Base Styles -->
		
		<link href="{{asset('assets/vendor/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/vendor/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{{asset('assets/img/user/favicon.ico')}}"/>
		<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/tabulator.css')}}" rel="stylesheet" type="text/css" />
		<style type="text/css">
		.tabulator{height:500px !important;}
		#ajax_data{margin-bottom:20px;}
		.actionbtn{text-decoration: none;    margin: 5px;}
		.actionbtn:hover{text-decoration: none;}
		.actionbtn>i{font-size: 1.6rem;color:#676769;}
		.actionbtn:hover i{color: #000000;}
	</style>
	</head>
		<!-- end::Body -->
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed-mobile m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<!-- begin::Header -->
				<header class="m-grid__item m-header "  data-minimize-mobile="hide" data-minimize-offset="200" data-minimize-mobile-offset="200" data-minimize="minimize" > 
				<div class="m-header__top">
					<div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
						<div class="m-stack m-stack--ver m-stack--desktop">
							<!-- begin::Brand -->
							<div class="m-stack__item m-brand">
								<div class="m-stack m-stack--ver m-stack--general m-stack--inline">
									<div class="m-stack__item m-stack__item--middle m-brand__logo">
										<a href="#" class="m-brand__logo-wrapper">
											<img width="170px" alt="" src="{{asset('assets/media/logos/logo-2.png')}}"/>
										</a>
									</div>
									
								</div>
							</div>
							<!-- end::Brand -->
							<!-- begin::Topbar -->
							<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
								<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
									<div class="m-stack__item m-topbar__nav-wrapper">
										<ul class="m-topbar__nav m-nav m-nav--inline">
											<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  " >
												<a href="{{asset('admin/home')}}" class="m-nav__link m-">
													<span class="m-topbar__userpic m--hide">
														<img src="{{asset('assets/media/logos/logo-2.png')}}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
													</span>
													
													<span class="m-topbar__username">
														Dashboard
													</span>
												</a> 
												
											</li>	
												<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown--skin-light" >
												<a href="{{asset('admin/userlist')}}" class="m-nav__link ">
													<span class="m-topbar__userpic m--hide">
														<img src="{{asset('assets/media/logos/logo-2.png')}}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
													</span>
													
													<span class="m-topbar__username">
														Employees
													</span>
												</a>
												
											</li>
											<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown--skin-light" >
												<a href="./logout" class="m-nav__link ">
													<span class="m-topbar__userpic m--hide">
														<img src="{{asset('assets/media/logos/logo-2.png')}}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
													</span>
													
													<span class="m-topbar__username">
														logout
													</span>
												</a>
												
											</li>	
											<li id="m_quick_sidebar_toggle" class="m-nav__item">
												<a href="#" class="m-nav__link m-dropdown__toggle">
													<span class="m-nav__link-icon m-nav__link-icon--aside-toggle">
														<span class="m-nav__link-icon-wrapper">
															<i class="flaticon-chat-1"></i>
														</span>
													</span>
												</a>
											</li>										
										</ul>
									</div>
								</div>
							</div>
							<!-- end::Topbar -->
						</div>
					</div>
				</div>
				
			</header>
			<!-- end::Header -->
			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					<div class="m-content" style="padding: 0px 0;">

						<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title" style="width: 100%">
										<h3 class="m-portlet__head-text">
											Employee List
<!--
											<small>
											 	Employees list
											</small>
-->
										</h3>
											<a style="float: right;margin-top: 15px;" href="javascript:;" onclick="addNew();"  class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
												<span>
													<i class="la la-user"></i>
													<span>
														Add Employee 
													</span>
												</span>
											</a>										
									</div>
									
								</div>
								
							</div>
							<div class="m-portlet__body">
								<!--begin: Search Form -->

								<!--end: Search Form -->
								<!--begin: Datatable -->
								<!-- <a href="javascript:void(0)" onclick="reset_table()">reset</a> -->
								
								<div class="m-form__group form-group">
							
									<div class="m-checkbox-list">
										<label class="m-checkbox">
											<input type="checkbox" id="resigned_employees"> Show Resigned Employees
											<span></span>
										</label>
									</div>
								</div>
                                <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Team</th>
                <th>Dept</th>
                <th>PE</th>
                <th>CE</th>
                <th>IE</th>
                <th>LOP</th>
                <th>WFH</th>
                <th>SW</th>
                <th>HT</th>
                <th>RT</th>
                <th>PT</th>
                <th>SLA</th>
                <th>EH</th>
                <th>MH</th>
                <th>DOJ</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
            @foreach($data as $dat)
            <tr>
                
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->fullname }}</td>
                <td>{{ $dat->designation }}</td>
                <td>{{ $dat->name }}</td>
                <td>{{ $dat->dep_name }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
                <td>{{ $dat->emp_id }}</td>
            </tr>
         @endforeach
        </tbody>
      
    </table>								<!--end: Datatable -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end:: Body -->
			<!-- begin::Footer -->
				<footer class="m-grid__item m-footer ">
				<div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
					<div class="m-footer__wrapper">
						<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
							<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
								<span class="m-footer__copyright">
								<?php echo date('Y'); ?>  &copy; PE System by
									<a href="#" class="m-link">
										HashRoot
									</a>
								</span>
							</div>
							<div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
								<ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
									<li class="m-nav__item">
										<a href="#" class="m-nav__link">
											<span class="m-nav__link-text">
												Performance Evaluation System
											</span>
										</a>
									</li>
									<li class="m-nav__item m-nav__item--last">
										<a href="#" class="m-nav__link" data-toggle="m-tooltip" title="Support Center" data-placement="left">
											<i class="m-nav__link-icon flaticon-info m--icon-font-size-lg3"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</footer>
			<!-- end::Footer -->
		</div>
		<!-- end:: Page -->
		<!-- begin::Scroll Top -->
		<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
			<i class="la la-arrow-up"></i>
		</div>
		<!-- end::Scroll Top -->

		<!-- Modal -->



		<div class="modal fade" id="updateModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<form  id="updateuser" class="m-form " action="./updateuser" method="post" >
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
								&times;
							</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">
							Update Details 
						</h4>
					</div>
					<div class="modal-body row ">
					<input name="userid" type="hidden"  id="user_id"/>
					 <div class="col-md-6">
						<div class="form-group m-form__group">
						<label for="exampleInputEmail1">
							Full Name
						</label>
						<input type="text" class="form-control m-input m-input--air" id="fullname" name="fullname" aria-describedby="emailHelp" placeholder="">	
						</div>
						<div class="form-group m-form__group">
						<label for="exampleInputEmail1">
							EMP ID
						</label>
						<input type="number" class="form-control m-input m-input--air" id="empid"name="empid" aria-describedby="emailHelp" placeholder="">	
						</div>
						<div class="m-form__group form-group ">
							<label for="">
								Gender 
							</label>
							<div class="m-radio-inline">
								<label class="m-radio">
									<input type="radio" name="gender" value="male">
									Male
									<span></span>
								</label>
								<label class="m-radio">
									<input type="radio" name="gender" value="female">
									Female
									<span></span>
								</label>
							</div>
						</div>
						<div class="form-group m-form__group ">
							<label for="exampleInputEmail1">
								Designation
							</label>
							<select class="form-control m-input m-input--air" id="designation" name="desgnn">
							     <option value=""></option>
							
							</select>
						</div>
						<div class="form-group m-form__group ">
							<label for="exampleInputEmail1">
								Team
							</label>
							<select class="form-control m-input m-input--air" id="team" name="team">
							
							</select>
						</div>
						
						<div class="form-group m-form__group ">
						<label for="exampleInputEmail1">
							Department
						</label>
						<select class="form-control m-input m-input--air" id="dept" name="dept">
						
						</select>
						</div>

						<div class="form-group m-form__group">
							<label for="exampleSelect2">Select Certifications</label>
							<input type="text" name="cert_list" id="cert_list" class="form-control m-input m-input--air m-input--pill">
							<!-- <select multiple="multiple" class="form-control m-input m-input--air m-input--pill" id="cert_list" name="cert_list[]">
							
							</select> -->
						</div>
						
						
						
					  </div>
					  <div class="col-md-6">
						<div class="form-group m-form__group">
						<label for="exampleInputEmail1">
							Email 
						</label>
						<input type="email" class="form-control m-input m-input--air" id="email"  name="email" aria-describedby="emailHelp" placeholder="">											
						</div>
						<div class="form-group m-form__group">
						<label for="exampleInputEmail1">
							Password
						</label>
					<input type="password" class="form-control m-input m-input--air" id="password" name="password" aria-describedby="emailHelp" placeholder="Enter password">	
						</div>
						<div class="form-group m-form__group">
						<label for="exampleInputEmail1">
							Phone
						</label>
						<input type="text" class="form-control m-input m-input--air" id="phone" name="phone" aria-describedby="emailHelp" placeholder="">	
						</div>
							<div class="form-group m-form__group ">
						<label for="exampleInputEmail1">
							Date of Join 
						</label>
						<input type="date" class="form-control m-input m-input--air" id="date_of_join" name="date_of_join" aria-describedby="emailHelp" placeholder="">	
						</div>
						<div class="form-group m-form__group ">
						<label for="exampleInputEmail1">
							DOB 
						</label>
						<input type="date" class="form-control m-input m-input--air" id="dob" name="dob" aria-describedby="emailHelp" placeholder="">	
						</div>


						<div class="m-form__group form-group">
							
							<div class="m-checkbox-list">
								<label class="m-checkbox">
									<input type="checkbox" id="core_employee"> Core Employee
									<span></span>
								</label>
							</div>
						</div>

						<div class="m-form__group form-group">
							
							<div class="m-checkbox-list">
								<label class="m-checkbox">
									<input type="checkbox" id="notice_period"> Notice Period
									<span></span>
								</label>
							</div>
						</div>

						<div class="m-form__group form-group">
							
							<div class="m-checkbox-list">
								<label class="m-checkbox">
									<input type="checkbox" id="wfh"> Limit Work From Home
									<span></span>
								</label>
							</div>
						</div>

						<div class="form-group m-form__group ">
							<label for="exampleInputEmail1">
								Buddy Assigned 
							</label>
							<input type="text" class="form-control m-input m-input--air" id="buddy_assigned" name="buddy_assigned">	
						</div>

					  </div>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
						<button type="submit" class="btn btn-primary">
							Save 
						</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	<!--  JD Skill set updater model  -->
	<div class="modal fade" id="skill_updater" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="padding: 10px;font-size: 13px;">
					Update Skill set
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
								&times;
							</span>
					</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<form  id="add_new_skill" class="m-form " action="./addNewSkill" method="post" >
								<input type="hidden" name="user_id" class="form-control" id="skill_user_id">
									<div class="form-group">
										<label class="control-label"style="font-size: 13px;">Add skill</label>
											<div class="input-group">
												<span class="input-group-addon">
												<i class="fa fa-asterisk"></i>
												</span>
												<input type="text" class="form-control" id="skill_name" name="skillname" placeholder="Mention skill here....">
											</div>
									</div>
									<div class="form-group text-right">
										<button type="submit" style="font-size: 13px;" class="btn btn-default">+ add</button>
									</div>
								</form>
								<h6 style="border-bottom: 1px solid #22a6fb;padding-bottom: 4px;">Skills added</h6>
								<div id="skill_added"></div>
							</div>
							<div class="col-md-6" style="border-left: 1px solid #f1f1f1;">
								<h6 style="border-bottom: 1px solid #fbdc22; padding-bottom: 4px;">Added for Review</h6>
								<div id="skill_review"></div>
								<h6 style="border-bottom: 1px solid #22fbec; padding-bottom: 4px;" sty>Skills Completed</h6>
								<div id="skill_completed"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
	</div>
							
	<!--  JD Skill set updater model  -->
		<!--begin::Base Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

        <script rel="stylesheet" type="text/css" src="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready(function() {
    $('#example').DataTable();
    
} );
</script>
	</body>
	<!-- end::Body -->
	
	</html>