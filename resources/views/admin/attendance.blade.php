@extends('layouts.admin_main')
@section('title')
        <title>
		Autowelkin One |  {{$data->name}}
        </title>
@endsection
@section('main_content')
<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Attendance 
											
										</h3>
									</div>
								</div>
								<div class="m-portlet__head-tools">
									
									
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="row">
								<div class="col-md-12">
								<form class="m-form m-form--fit m-form--label-align-right" action="javascript:;"  method="post" id="attendance_dat_form">
								<div class="m-portlet__body">
<!--								test code for search and dropdown-->
									<div class="col-md-4" style="float:left;"> 
										<div class="form-group m-form__group row m-form ">
												
												<input list="emps" name="emps" type="text" id="emp_input" placeholder="Select an Employee" class="form-control m-input">
												<datalist id="emps">
												
												  <option data-value="" value=""  ><b></b></option>
<!--	 <input type="hidden" name="test">-->
												 
												</datalist>
											</div>
										</div>
<!--	close	test code for search and dropdown-->
								<div class="col-md-8" style="float:right;">
									<div class="form-group m-form__group row">

											<label class="col-form-label col-lg-4 col-sm-12">
												Select a month
											</label>
<!--											<input type="hidden" class="form-control m-input" name="new_user_id" value="<?php //echo $emps['user_id']?>">-->
											<input id="month_user_id" type="hidden"  class="form-control m-input" name="user_id" value="">
											<div class="col-lg-6 col-md-9 col-sm-12">
												<div class="input-group date" id="month_pick_attendance">
													<input id="" type="text" class="form-control m-input" name="month_pick_attendancedat" placeholder="Select Here"/>
													<span class="input-group-addon">
														<i class="la la-calendar-check-o"></i>
													</span>
													<div class="col-lg-4 col-md-3 col-sm-3"><button class="btn btn-primary m-btn m-btn--custom" type="submit" name="submit" value="view details">Proceed</button> </div>
												</div>
												
											</div>
										</div>
										</div>
									</div>
								</form>
								<div class="m-portlet__body" id="new-div-attendance"></div>
								<div class="m-portlet__body" id="new-div-attendance2"></div>
								</div>






							</div>
						</div>
					</div>
				</div>
			</div>

@endsection