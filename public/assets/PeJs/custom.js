
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
//performance form
$('#addperformance').ajaxForm(function()
	{
		$.notify({
				title: '<strong>Success!</strong>',
				message: 'You have added successfully.'
			},{
				type: 'success'
			});
	}); 
	

	$('#adddept').ajaxForm({
		dataType:'json', 
		success: function(response, status, xhr, $form) {
			if(response==1){								
		$.notify({
				title: '<strong>Success!</strong>',
				message:"Dept Added"
			},{
				type: 'success',
				z_index: 10000,
			});
			}
		
		else{
			$.notify({
				title: '<strong>Error!</strong>',
				message:"Dept couldn't be added!"
			},{
				type: 'danger',
				z_index: 10000,
			});
		}
		}
	}); 


	$('#addteam').ajaxForm({
		dataType:'json', 
		success: function(response, status, xhr, $form)  {
			if(response==1){								
		$.notify({
				title: '<strong>Success!</strong>',
				message:"Team Added"
			},{
				type: 'success',
				z_index: 10000,
			});
			}
		
		else{
			$.notify({
				title: '<strong>Error!</strong>',
				message:"Team couldn't be added!"
			},{
				type: 'danger',
				z_index: 10000,
			});
		}
		}

	});

function viewallteam(){ 
		$.ajax({
			dataType:'json',
			url:'./viewteams',
			success:function(data){
				table ='';
				for (var i = 0, len = data.length; i < len; i++) {
					table +='<tr>';
					table +='<td>'+(i+1)+'</td><td id="td_id_'+data[i].team_id+'"><span id="Ed_team_id_'+data[i].team_id+'">'+data[i].name+'</span><td id="td_mail_in_'+data[i].team_id+'"><span id="ml_id_'+data[i].team_id+'"><input id="ml_in_'+data[i].team_id+'" type="text" value="'+data[i].mail_ids+'" name="mail" disabled="disabled"/></span></td><td><button class="btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--pill" onclick="delete_team('+data[i].team_id+')" style="float:right;">  <i class="la la-trash"></i></button><button class="btn btn-outline-accent btn-sm m-btn m-btn--icon m-btn--pill" id="atag_id_'+data[i].team_id+'" data="Edit" style="float:right;margin-right: 2px;" onclick="edit_teams('+data[i].team_id+',this);return false;">Edit</button> </td>';
				//console.log(data[i].name);
					table +='<tr>';
				}
				$('#teamDiv').html(table);
				$('#viewteam').modal('show');     
				
			}
		});
		
	}

function delete_team(team_id){
	var result = confirm("Are you sure you want to Delete this Team? If you delete ,this will affect further datas which are related to this department and employee details. ");
	if(result){
			event.preventDefault();
			$.ajax({
						url:'./delete_teams_nview',
						dataType:'json',
						data:{team_id},
						type:'POST',
						success:function(data){ 
//							alert(team_id);
//							alert(data);
//							console.log(data.stat.flag); 
							if(data.stat.flag == 1){
								$.notify({
									title: '<strong>Success!</strong>',
									message:data.stat.msg
								},{
									type: 'success',
									z_index: 10000,
								});
							}
							else{
								$.notify({
									title: '<strong>Error!</strong>',
									message:data.stat.msg
								},{
									type: 'danger',
									z_index: 10000,
								});
							}


						}
					});
	}
}

	function viewalldept(){
		$.ajax({
			dataType:'json',
			url:APP_URL+"/viewdepts",
			success:function(data){
				table ='';
				for (var i = 0, len = data.length; i < len; i++){
					table +='<tr>';
					table +='<td>'+(i+1)+'</td><td id="td_id_'+data[i].dep_id+'"><span id="Ed_id_'+data[i].dep_id+'">'+data[i].dep_name+'</span><button class="btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--pill"  onclick="delete_dep('+data[i].dep_id+')" style="float:right;">  <i class="la la-trash"></i></button><button class="btn btn-outline-accent btn-sm m-btn m-btn--icon m-btn--pill" id="atag_id_'+data[i].dep_id+'" data="Edit" style="float:right;margin-right: 2px;" onclick="edit_dept('+data[i].dep_id+',this)">Edit</button> </td>'; 
					//console.log(data[i].name);
					table +='<tr>';
				}
				$('#deptDiv').html(table);
				$('#viewDept').modal('show');     
				
			}
		});
		
	}
	f
//Edit departments
function edit_dept(dep_id,obj){
	event.preventDefault();
	var a_val = $(obj).attr('data');
	if(a_val == "Edit"){
		$(obj).attr('data','Save');
		$(obj).text('Save');
		var text = $('#Ed_id_'+dep_id).text();
		$('#Ed_id_'+dep_id).html('<input value="'+text+'" name="dep_name">');
	}
	else{
		var text2 = $('#Ed_id_'+dep_id+' input').val();
		$(obj).attr('data','Edit');
		$(obj).text('Edit ');
		$.ajax({
				url:APP_URL+"/editdepts/"+dep_id,
				data:{dep_id,text2},
				type:'PUT',
				success:function(response){ 
					console.log(response);
					if(response.status) {
						$('#Ed_id_'+dep_id).html(text2);
						$.notify({
							title: '<strong>Success!</strong>',
							message:"Department Updated"
						},{
							type: 'success',
							z_index: 10000,
						});
					} else {
						$.notify({
							title: '<strong>Error!</strong>',
							message:"Department not updated"
						},{
							type: 'danger',
							z_index: 10000,
						});
					}	
				},
				error: function(response) {
					$.notify({
						title: '<strong>Error!</strong>',
						message:"Department not updated"
					},{
						type: 'danger',
						z_index: 10000,
					});
				}
			});
		
	}

}
/*** Edit teams */
function edit_teams(team_id,obj){
	var a_val = $(obj).attr('data');
	if(a_val == "Edit"){
		$(obj).attr('data','Save');
		$(obj).text('Save');
		var text = $('#Ed_team_id_'+team_id).html();
		$('#Ed_team_id_'+team_id).html('<input value="'+text+'" name="team_name"/>');
		$('#ml_in_'+team_id).removeAttr('disabled','disabled');
	}
	else{
		var mail_ids = $('#ml_id_'+team_id+' input').val();
		var text2 = $('#Ed_team_id_'+team_id+' input').val();
		$(obj).attr('data','Edit');
		$(obj).text('Edit');
		$('#ml_in_'+team_id).attr('disabled','disabled');
		$.ajax({
				url:'./editteams',
				data:{team_id,text2,mail_ids},
				type:'POST',
				dataType:'json',
				success:function(data){ 
					if(data.flag == 0){
						$.notify({
							title: '<strong>Error!</strong>',
							message:data.msg
						},{
							type: 'danger',
							z_index: 10000,
						});
					}else{
						$.notify({
							title: '<strong>Success!</strong>',
							message:data.msg
						},{
							type: 'success',
							z_index: 10000,
						});
					}
					$('#Ed_team_id_'+team_id).html(text2);	

				}
			});
		
	}
   
}
//Delete department
function delete_dep(dep_id){
	console.log(dep_id);
	var result = confirm("Are you sure you want to Delete this Department? If you delete ,this will affect further tables and datas which are related to this department . ");
	if(result){
			event.preventDefault();
			$.ajax({
						url:APP_URL+'/delete_depts/'+dep_id,
						data:{dep_id},
						type:'DELETE',
						dataString:"json",
						success:function(response){ 
							console.log(response);
							if(response.status == 1){
								viewalldept();
								$.notify({
									title: '<strong>Success!</strong>',
									message:"Successfully deleted a department"
								},{
									type: 'success',
									z_index: 10000,
								});
							}
							else{
								$.notify({
									title: '<strong>Error!</strong>',
									message:response.message
								},{
									type: 'danger',
									z_index: 10000,
								});
							}


						}
					});
	}
			
}
//Close delete department
//add jd form
$('#addJD_form').ajaxForm({
		dataType:'json',
		success:function(response,status,xhr,$form){
			alert(response);	
		}
	}); 
	




//Close add jd forms

$('.btn-number').click(function(e){
    e.preventDefault();
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    value      = $(this).attr('data-value');

    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    var old_val = input.attr('data-old-value');
    if (!isNaN(currentVal)) {
        if(type == 'minus')
            input.val(currentVal - value);
        else if(type == 'plus')
            input.val(currentVal-(-value));      
    } else {
        input.val(0);
    }

    activate_button(fieldName, old_val);
});

	function updatepoint(field,user_id,old_val){

		var new_value=$('#'+field).val();
		change= new_value -old_val;
		$('#'+field+'_'+user_id).html(' ');
		//console.log($(this));
		$('#'+field+'_'+user_id).addClass('m-loader m-loader--light m-loader--sm' );
		// console.log($("#"+field+"_comment").val());
		var comment = $("#"+field+"_comment").val();
		var result = confirm("Are you sure you want to save this change ?");
		if(result){
			$.ajax({
			url:'../updatepoint/'+user_id,
			type:'POST',
			data:{field:field,value:change,new_value:new_value,comment:comment},
			success:function(data){
				if(data==true){
					$('#'+field).attr('data-old-value', new_value);
					$("#"+field+"_comment_btn").addClass("hide");
					$.notify({
				title: '<strong>Updated!</strong>',
				message: 'Updated successfully.'
			},{
				type: 'success',
				z_index: 10000,
			});	
		$('#'+field+'_'+user_id).attr("onclick","updatepoint('"+field+"',"+user_id+","+new_value+");");				
				}else{
			$.notify({
				title: '<strong>Error!</strong>',
				message: 'Something went wrong!'
			},{
				type: 'danger',
				z_index: 10000,
			});
				
				}
				$('#'+field+'_'+user_id).html('<i class="fa fa-floppy-o"></i>' );	
				$('#'+field+'_'+user_id).removeClass('m-loader m-loader--light m-loader--sm' );	
			}
			
		});
		}
		 
	}

	function open_comment_modal(field, user_id){
		$("#new-comment").val('');
		$("#field_performance_id").val(user_id);
		$("#field_name").val(field);
		$("#add_comment").modal('toggle');
	}

	function save_comment(){
		var field = $("#field_name").val();
		var comment = $("#new-comment").val();
		if(comment == ""){
			alert("Please add some comment");
			return false;
		}
		var performance_id = $("#field_performance_id").val();
		console.log(comment);
		console.log(field);
		$("#"+field+"_comment").val(comment);
		$("#"+field+"_"+performance_id).click();
		$("#add_comment").modal('toggle');

	}

	function activate_button(field, old_val){
		
		var new_value = $("#"+field).val();
		
		if(new_value != old_val){
			$("#"+field+"_comment_btn").removeClass("hide");
		}else{
			$("#"+field+"_comment_btn").addClass('hide');
		}
	}

	function open_evaluation_detail(field, per_id){
		console.log(field);
		$.ajax({
			url:'../get_evaluation_details/',
			type:'POST',
			dataType: 'json',
			data:{performance_id:per_id, field:field},
			success: function(result){
				
				if(result.status == true){
					var data = result.data;
					$("#evaluation_details").html('');
					$("#evaluation_title").html(field);
					$(data).each(function(){
						
						$("#evaluation_details").append('\
							<tr>\
								<td>'+this.score+'</td>\
								<td>'+this.date+'</td>\
								<td>'+this.comment+'</td>\
							</tr>\
							');
					});

					$("#evaluation_detail_modal").modal('toggle');
				}else{
					$.notify({
						title: '<strong>Error!</strong>',
						message: result.message
					},{
						type: 'danger',
						z_index: 10000,
					});
				}
			}
		});
	}

	
	function adminsettings(){ 
		$('#adminsettings').modal('show'); 
		}

		$('#adminupdate').ajaxForm({
		success: function(response, status, xhr, $form){
					if(response==1){								
		$.notify({
				title: '<strong>Success!</strong>',
				message:"Upadted!"
			},{
				type: 'success',
				z_index: 10000,
			});
			}
		
		else{
			$.notify({
				title: '<strong>Error!</strong>',
				message:"Something went wrong!"
			},{
				type: 'danger',
				z_index: 10000,
			});
		}
			}
		});
		
//Attendance module  starts here
if($('#btnpunchin').attr('disabled')=='disabled'){		
	$('#btnbreak').removeAttr('disabled');	
	$('#btnpunchout').removeAttr("disabled");	
	$('#work_loc').attr("disabled","disabled");
}else{
	$('#btnpunchout').attr('disabled','disabled');	
	$('#btnbreak').attr('disabled','disabled');
	$('#work_loc').removeAttr('disabled');		
}
var getin_stat = $('#btnbreak').attr('break');

if(getin_stat == 'on'){
	console.log(getin_stat);
	$('#btnpunchout').attr('disabled','disabled');
}
//work location start
function work_loc(obj){ 
	$('#btnpunchin').attr("onclick","punchIn("+(obj.value)+")");
if(obj.value==5){
$("#restricted_wfh").modal("show");
}
	
}

$('#work_loc').on('change',function(){
	workloc=$(this).val();
	$('#btnpunchin').attr("onclick","punchIn("+workloc+")");
});
//work location ends


	function punchIn(work_loc){
		var result = confirm("Are you sure you want to Punch In?");

	if (result) {
	
		//notification
			if($('#notification_catcher').val()==0){
				$('#newnotification').modal('show');
			}
		
		
		$.ajax({
			url:'punchin',
			data:{work_loc},
			type:'POST',
			dataType:'json',
			success:function(result){ 
				console.log(result);
				if(result.status == true){
					if(work_loc == 2){
						start_wfh_timer();
					}
					var data = result.time;
					$('#work_loc').attr("disabled","true");	
					$('#punchin').html('<span class="m-badge m-badge--brand m-badge--rounded m--font-bolder" style="font-size: 11px;">Punched in : '+data+'</span>');
					$('#btnpunchin').attr('disabled','disabled');	
					$('#btnpunchout').removeAttr('disabled','disabled');	
					$('#btnbreak').removeAttr('disabled','disabled');
					get_daily_acts();
				}else{
					if(result.message == 'home login exceeded'){
						var confirmation = confirm("You have used up the available WFH option. Please refer instructions before porceeding.");
						if (confirmation == true){
							var casual_leave = true;
							$.ajax({
								url: 'punchin',
								data: {work_loc,casual_leave},
								type: 'POST',
								dataType: 'json',
								success: function(response){
									console.log(response);
									if(response.status == true){
										var data = response.time;
										$('#work_loc').attr("disabled","true");	
										$('#punchin').html('<span class="m-badge m-badge--brand m-badge--rounded m--font-bolder" style="font-size: 11px;"> Punched in : '+data+' </span>');
										$('#btnpunchin').attr('disabled','disabled');	
										$('#btnpunchout').removeAttr('disabled','disabled');	
										$('#btnbreak').removeAttr('disabled','disabled');
										get_daily_acts();
									}else{
										alert(response.message);
									}
								}
							});
						}
					}else{
						alert(result.message);
					}
				}
				
			}
			});
	}
	}
$('#btnbreak').on('click',function(){
	
	
	var breakstatus=$(this).attr('break');
	
	//Start Confirmation
	var result = confirm("Are you sure?");

	console.log(result);

	/*if(result == true){
		$.ajax({
			url:'breaktime',
			type:'POST',
			data:{breakstatus},
			dataType:'json',
			success:function(data){
				console.log('code is here');
				if(data.status == false){
					alert(data.message);
					throw new Error("Something went badly wrong!");
				}
				
				if(breakstatus=='off'){
					$(this).attr('break','on');
					$(this).html('Get in');
					$('#btnpunchout').attr('disabled','disabled');	
				}else{
					$(this).attr('break','off');
					$(this).html('Break');
					$('#btnpunchout').removeAttr('disabled','disabled');	
				}
				$('#breaktime').html(data);
				
			}
		});
	}*/
	console.log(this);
	if (result) {
	if(breakstatus=='off'){
		$(this).attr('break','on');
		$(this).html('Get in');
		$('#btnpunchout').attr('disabled','disabled');	
	}else{
		$(this).attr('break','off');
		$(this).html('Break');
		$('#btnpunchout').removeAttr('disabled','disabled');	
	}
	$.ajax({
			url:'breaktime',
			type:'POST',
			data:{breakstatus},
			dataType:'json',
			success:function(data){
				if(data.status == false){
					alert(data.message);
					console.log(breakstatus);
					if(breakstatus=='off'){
						$("#btnbreak").attr('break','off');
						$("#btnbreak").html('Break');
						$('#btnpunchout').removeAttr('disabled','disabled');	
					}else{
						$("#btnbreak").attr('break','on');
						$("#btnbreak").html('Get in');
						$('#btnpunchout').attr('disabled','disabled');
					}
					/*$("#btnbreak").attr('break','off');
					$("#btnbreak").html('Break');
					$('#btnpunchout').removeAttr('disabled','disabled');*/

					// throw new Error("Something went badly wrong!");
				}else{
					$('#breaktime').html('<span class="m-badge m-badge--warning m-badge--rounded" style="font-size:11px;">'+data.time+'</span>');
					start_wfh_timer();
				}
				
				
			}
			});
	}
});

$(document).ready(function(){
	start_wfh_timer();
});

function start_wfh_timer(){
	var number = Math.floor(Math.random() * 10000000);
	var check_break = $("#btnbreak").attr('break');
	var work_loc = check_work_loc();

	/*if(work_loc == 2){
		if(check_break == 'off'){
			console.log(number);
			setTimeout(function(){
				start_wfh_break()
			}, number);
		}
	}*/
}

function check_work_loc(){
	var workloc;
	$.ajax({
		url:'get_work_loc',
		type:'GET',
		async: false,
		dataType:'json',
		success:function(result){
			console.log(result);
			if(result.status == true){
				workloc = parseInt(result.work_loc);
			}else{
				workloc = false;
			}
		}
	});

	return workloc;
}
	
function start_wfh_break(){

	var check_break = $("#btnbreak").attr('break');
	if(check_break != 'off'){
		start_wfh_timer();
		return false;
	}

	$.ajax({
		url:'start_wfh_break',
		type:'GET',
		dataType:'json',
		success:function(result){
			console.log(result);
			if(result.status == true){
				alert_fcm();
				var confirmation = confirm("Please click OK to continue");
				if(confirmation == true){
					deactivate_wfh_break();
				}else{
					deactivate_wfh_break();
				}
			}
		}
	});
}

function deactivate_wfh_break(){
	$.ajax({
		url:'end_wfh_break',
		type:'GET',
		dataType:'json',
		success:function(result){
			if(result.status== true){
				start_wfh_timer();
			}
		}
	});

}

$('#btnpunchout').on('click',function(){
	
	//Start confirmation
	var result = confirm("Are you sure you want to Punch Out?");

	if (result) {
	
		$.ajax({
			url:'punchout',
			dataType:'json',
			type:'POST',
			success:function(result){
				if(result.status == true){
					var data = result.data;
						$('#btnpunchout').attr('disabled','disabled');	
						$('#btnbreak').attr('disabled','disabled');	
						$('#punchout').html('<span class="m--font-bolder m-badge m-badge--darkgrey m-badge--rounded" style="margin-right:100px;font-size:11px;font-weight:600;">Punched Out : '+data.punchout+'</span> <span class="m--font-bolder m-badge m-badge--blue m-badge--rounded" style="font-size:11px;font-weight:600;" >Total work hours : '+data.worked+' </span> ');
						if(data.break!=''){
							$('#breaktime').html('<span class="m-badge m-badge--warning m-badge--rounded" style="font-size:11px;font-weight:600;"> Total Break Taken '+data.break+'</span');
						}
						

						//update weekly status
						$('#pending_hrs').html(data.pending_hrs);
						$('#wrkd_hrs').html(data.wrking_hrs);
						$('#extra_hrs').html(data.extra_hrs);
						$('#flexi_hrs').html(data.flexi_hrs);
						$('#overtime').html(data.overtime);
						//tot hrs
					
					// datahrs ='';
					// datahrs += '<div class="col-md-12 row" style="text-align: center;">\
					// \<div class="col-md-2 col-md-offset-1">	<span class="m-badge m-badge--danger m-badge--rounded "><b> Mandatory Hours </b><br><span id="pending_hrs"><b>'+data.pending_hrs+'</b></span></span></div>\
					// \<div class="col-md-2">	<span class="m-badge m-badge--primary m-badge--rounded "><b> Worked Hours  </b><br><span id="wrkd_hrs"><b>'+data.wrking_hrs+'</b></span> </span> </div>\
					// \<div class="col-md-2">	<span class="m-badge m-badge--warning m-badge--rounded">	<b> Extra Hours  </b><br><span id="extra_hrs"><b>'+data.extra_hrs+'</b></span></span></div>\
					// \<div class="col-md-2">	<span class="m-badge m-badge--success m-badge--rounded">	<b> Overtime  </b><br><span id="overtime"><b>'+data.overtime+'</b></span></span></div>\
					// \<div class="col-md-2">	<span class="m-badge m-badge--brand m-badge--rounded">	<b> Flexi Hours </b><br><span id="overtime"><b>'+data.flexi_hrs+'</b></span></span></div>\
					// \</div>';
					// $('#hours_1').html('');	
					// $('#hours').css("padding","10px 15px 8px 15px"); 				
					// $('#hours').html(datahrs); 
					//tot hrs
				}else{
					alert(result.message);
				}
				

			} 
			});
	}//end confirmation
	});
//Attendance module  ends  here


//Requests Module  starts here 
	function viewallrequests(){
			$.ajax({
			url:'viewallrequests',
			type:'POST',
			success:function(data){			
				$('#pending-apps').html(data);		
			}
			});
	}





$('#request_button').click(function(e) {
	
	
	var result = confirm("Are you sure that you want to send this request ?");
	if(result){

	$('#request').ajaxForm({
		dataType:'json', 
		cache:false,
            contentType: false,
            processData: false,
		success: function(response, status, xhr, $form){
			if(response.status==0){		
			$('#pending-apps').prepend(response.html);						
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.msg
				},{
					type: 'success',
					z_index: 10000,
				});
				$('#request').clearForm();
			}
		
		else{
				$.notify({
					title: '<strong>Error!</strong>',
					message:response.error
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}

	}); 
		}else{
		return false;
	}

});




//Requests Module  ends here

//start add jd

	function viewalldepts_jd(){
			$.ajax({
			dataType:'json',
			url:'./viewdepts',
			success:function(data){
				dep_list ='';
				dep_list +='<select id="sel" class="form-control m-input" name="select_dept2" onchange="get_jd(this)"><option value="0">Select a department</option>';
				for (var i = 0, len = data.length; i < len; i++) {
					
					dep_list +='<option value="'+data[i].dep_id+'">'+data[i].dep_name+'</option>';
				//console.log(data[i].name);
					
				}
				dep_list +='</select>';
				$('#show_dep_jd').html(dep_list);
     			$('#addJD').modal('show');      			
//				
			}
		});
		
	}
// Department dropdown
//Get jd from db
function get_jd(obj){
		if(obj.value!=0){	
	var activity_list='';	
		$.ajax({
			dataType:'json',
			url:'./getjd',
			type:'post',
			data:{dep_id:obj.value},
			success:function(data){
				console.log(data.job_desc);
				$('#jd').html(data.job_desc);					
				if(data.activities.length<=0){
					$('#activity_lists').html('<div class="text-center">No Activities</div>');
				}else{							
				for (var i = 0, len = data.activities.length; i < len; i++) {
					activity_list +='<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="deleteActivity('+data.activities[i].daily_act_id+')"></button>'+data.activities[i].daily_act+'</div>';
				}
				$('#activity_lists').html(activity_list);				
				}
			}
			
		});
}else{
	$('#jd').html('Please select a department');
}
}

//close getting jd
//Add new activity

function add_new_act(){	

			var daily_activity= $('#daily_activity').val();
			var field_type_id = $('#daily_fieldType_id').val();
			var depmnt= $('#sel').val();
			if(depmnt!=0){
				
			
			$.ajax({
			//dataType:'json',
			url:'./add_new_act',
			type:'post',
			data:{daily_activity:daily_activity,depmnt:depmnt,field_type_id},
			success:function(data){	
			//alert('cgbhfghf');
				
					$('#activity_lists').append('<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"  onclick="deleteActivity('+data+')" ></button>'+daily_activity+'</div>'); 
			}			
		});
}else{
	alert('Please select department first');
}
}
//Close add new act
//Delete  activity
function deleteActivity(activity_id){
		$.ajax({
			dataType:'json',
			url:'./delete_Activity',
			type:'POST',
			data:{activity_id},
			success:function(data){	
							if(data==1){
					$.notify({
				title: '<strong>Updated!</strong>',
				message: 'Removed successfully.'
			},{
				type: 'success',
				z_index: 10000,
			});			
				}else{
			$.notify({
				title: '<strong>Error!</strong>',
				message: 'Something went wrong!'
			},{
				type: 'danger',
				z_index: 10000,
			});
				
				}
			}
		});
}

//Close deleting
//Add new activity tag in worksheet
function viewalldepts(){
		$.ajax({
			dataType:'json',
			url:'./viewdepts',
			success:function(data){
				
				dep_list ='';
				dep_list +='<select id="sel" name="select_dept[]" multiple="multiple">';
				for (var i = 0, len = data.length; i < len; i++) {
					
					dep_list +='<option value="'+data[i].dep_id+'">'+data[i].dep_name+'</option>';
				//console.log(data[i].name);
					
				}
				dep_list +='</select>';
				$('#show_dep').html(dep_list);
     			$('#addactivity').modal('show');  
//				
			}
		});
		
	}
//Close Add new activity tag in worksheet

$('#activity_form').ajaxForm({
		dataType:'json',
		type:'post',
		success: function(response, status, xhr, $form) {
//			alert(response);
			console.log(response);
				if(response==0){	
					$.notify({
				title: '<strong>Error!</strong>',
				message:"Please select department and date !"
			},{
				type: 'danger',
				z_index: 10000,
			});
		
			}
		
		else{
			
			$.notify({
				title: '<strong>Success!</strong>',
				message:"Activity added"
			},{
				type: 'success',
				z_index: 10000,
			});
			
		}
			$('#task_id').val('');
			$('#task_name_id').val('');
//			alert(response);
		}
	}); 
//close add activity

//start view Status of worksheet
function view_status(){
//	alert('Hii');
	$.ajax({
		url:'./view_stat',
		type:'post',
		success:function(data){
//			alert('Welcome');
			console.log(data);
			
			$('#worksheet_status').html(data);
			$('#view_stat').modal('show');  
		}
		
	});
}
//End status of worksheet

function monthly_daily_status(id){
	
	$.ajax({
		url:'./daily_datas',
		type:'post',
		data:{user_id:id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-dailystat').html(data);
			$('#month_user_id').val(id);
		}
	});
}
function monthly_weekly_status(id){ 	
	$.ajax({
		url:'./weekly_datas',
		type:'post',
		data:{user_id:id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-weekstat').html(data);
			$('#month_user_id').val(id);
		}
	});
}
function monthly_monthly_status(id){
		$.ajax({
		url:'./monthly_datas',
		type:'post',
		data:{user_id:id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-monthlystat').html(data);
			$('#month_user_id').val(id);
		}
	});
}
//Admin datepicker
$('#daily_status_emp').on('submit', function(event)
		{
			
			event.preventDefault();
//			alert('hii');
			var dataString = $("#daily_status_emp").serialize();
			var url="./daily_datas"
			$.ajax(
				{
					type:"POST",
					url:url,
					data:dataString,
					success:function(data)
					{
						
						console.log(data);
						var userid = $('#month_user_id').val();
						if(userid==''){
							$.notify({
								title: '<strong>Error!</strong>',
								message:"Please Select an Employee!"
							},{
								type: 'danger',
								z_index: 10000,
							});
//							$('#new-div-admin2').show().html(data); 
							$("#daily_status_emp")[0].reset();
						}
						else{
//							alert(userid);
//							$('#new-div-admin2').show().html(data);
							$('#new-div-dailystat').html(data);
							$("#daily_status_emp")[0].reset();
						}
					
						
					}
				});
				return false;
		})
//Close admin datepicker

//weekly stat starts
$('#weekly_status_emp').on('submit', function(event)
		{
			
			event.preventDefault();
//			alert('hii');
			var dataString = $("#weekly_status_emp").serialize();
			var url="./weekly_datas"
			$.ajax(
				{
					type:"POST",
					url:url,
					data:dataString,
					success:function(data)
					{
						
						console.log(data);
						var userid = $('#month_user_id').val();
						if(userid==''){
							$.notify({
								title: '<strong>Error!</strong>',
								message:"Please Select an Employee!"
							},{
								type: 'danger',
								z_index: 10000,
							});
//							$('#new-div-admin2').show().html(data); 
							$("#weekly_status_emp")[0].reset();
						}
						else{
//							alert(userid);
//							$('#new-div-admin2').show().html(data);
							$('#new-div-weekstat').html(data);
//							$('#new-div-weeklystat').html(data);
							$("#weekly_status_emp")[0].reset();
						}
					
						
					}
				});
				return false;
		})
//weekly stat ends 

//monthly stat starts 
$('#monthly_status_emp').on('submit', function(event)
		{
			event.preventDefault();
//			alert('hii');
			var dataString = $("#monthly_status_emp").serialize();
			var url="./monthly_datas"
			$.ajax(
				{
					type:"POST",
					url:url,
					data:dataString,
					success:function(data)
					{
						
						console.log(data);
						var userid = $('#month_user_id').val();
						if(userid==''){
							$.notify({
								title: '<strong>Error!</strong>',
								message:"Please Select an Employee!"
							},{
								type: 'danger',
								z_index: 10000,
							});
//							$('#new-div-admin2').show().html(data); 
							$("#monthly_status_emp")[0].reset();
						}
						else{
//							alert(userid);
//							$('#new-div-admin2').show().html(data);
							$('#new-div-monthlystat').html(data);
							$("#monthly_status_emp")[0].reset();
						}
					
						
					}
				});
				return false;
		})
//monthly stat ends 
//Close admin datepicker

//attendance stat starts 
$('#attendance_dat_form').on('submit', function(event)
		{
			event.preventDefault();
//			alert('hii');
			var dataString = $("#attendance_dat_form").serialize();
			var url="./attendance_datas"
			$.ajax(
				{
					type:"POST",
					url:url,
					data:dataString,
					success:function(data)
					{
						
						console.log(data);
						var userid = $('#month_user_id').val();
						if(userid==''){
							$.notify({
								title: '<strong>Error!</strong>',
								message:"Please Select an Employee!"
							},{
								type: 'danger',
								z_index: 10000,
							});
//							$('#new-div-admin2').show().html(data); 
							$("#monthly_status_emp")[0].reset();
						}
						else{
//							alert(userid);
//							$('#new-div-admin2').show().html(data);
							$('#new-div-attendance').html(data);
							$("#monthly_status_emp")[0].reset();
						}
					
						
					}
				});
				return false;
		})
//attendance stat ends


//Ticket Report Current month
function monthly_ticket_report(userid,depid){
	$.ajax({
		url:'./ticket_info_monthly',
		type:'post',
		data:{user_id:userid,dep_id:depid},
		success:function(data){
			$('#list-ticket-report').html(data);
			$('#wrk_user_id').val(userid);
			$('#wrk_dep_id').val(depid);
		}
	});
}
//Current month ticket report
//Ticket Report Current month .Please Dont delete it
//function monthly_ticket_report(id){
//	$.ajax({
//		url:'./ticket_info_monthly',
//		type:'post',
//		data:{user_id:id},
//		success:function(data){
//			$('#list-ticket-report').html(data);
//			$('#wrk_user_id').val(id);
//		}
//	});
//}
//Current month ticket report .Please Dont delete the commented function 
$(document).ready(function(){
	$('#images').on('change',function(){
		$('#upload_work_report').ajaxForm({
			target:'#uploadstatus',
			beforeSubmit:function(e){
				$('.uploading').show();
			},
			success:function(e){
				$('.uploading').hide();
			},
			error:function(e){
				$('.uploading').hide();

			}
		}).submit();
	});
});
//Monthly report of tickets in user section
//Date picker 
function reports_user_mod(){
	$('#reports_user_modal').modal('show');
	
	$.ajax({
			dataType:'html',
			url:'./reports_user',
			success:function(data){
			$('#list_reports_user').html(data);	 
			}
		});
	
	
}

$('#wrokReport').ajaxForm({
	dataType: 'json',
	success: function(result){
		// console.log(result);
		if(result.status == true){
			var data = result.data;
			$("#ticket_details_list").html('');
			$(data).each(function(){
				$("#ticket_details_list").append('\
						<tr>\
							<td>'+this.ticket_id+'</td>\
							<td>'+this.response+'</td>\
							<td>'+this.sla+'</td>\
						</tr>\
					');
			})
		}else{
			$("#ticket_details_list").html('');
		}
	}
	//	url:'./reports_user',
	/*dataType:'html', 
	success: function(response,status,xhr,$form){
	console.log(response);
	$('#list_reports_user').html(response);
	$("#wrokReport")[0].reset();

	}*/
}); 


$('#wrokReportedit').ajaxForm({
	dataType: 'json',
	success: function(result){
		// console.log(result);
		if(result.status == true){
			var data = result.data;
			$("#ticket_details_list").html('');
			$(data).each(function(){
				$("#ticket_details_list").append('\
						<tr>\
							<td>'+this.ticket_id+'</td>\
							<td>'+this.response+'</td>\
							<td>'+this.sla+'</td>\
						</tr>\
					');
			})
		}else{
			$("#ticket_details_list").html('');
		}
	}
	//	url:'./reports_user',
	/*dataType:'html', 
	success: function(response,status,xhr,$form){
	console.log(response);
	$('#list_reports_user').html(response);
	$("#wrokReport")[0].reset();

	}*/
}); 
//close date picker 

//Admin month picker report and ticket
$('#datepick_rep_form_Admin').ajaxForm({
	//url:'./reports_user',
	dataType:'html', 
	success: function(response,status,xhr,$form){
	console.log(response);
	$('#list-ticket-report').html(response);
    $("#datepick_rep_form_Admin")[0].reset();

	}
}); 
//Close ADmin month picker and ticket
//weekly Activity  starts
	function weeklyactivity(){
			$.ajax({
			dataType:'json',
			url:'./viewdepts',
			success:function(data){
				dep_list ='';
				dep_list +='<select id="sel" class="form-control m-input"  onchange="get_weekly_activity(this)"   name="select_dept2" ><option value="0">Select a department</option>';
				for (var i = 0, len = data.length; i < len; i++) {
					
					dep_list +='<option value="'+data[i].dep_id+'">'+data[i].dep_name+'</option>'; 
				//console.log(data[i].name);
					
				}
				dep_list +='</select>';
				$('#show_dep_wa').html(dep_list);
     			$('#addweekly').modal('show');      			
//				
			}
		});
		
	}
$('#addWA_form').ajaxForm({	
		dataType:'json',
		success: function(response,status,xhr,$form){
//			alert(response);
			if(response.status==1){
			
			$('#weekly_activity_lists').prepend('<div class="alert alert-dismissible fade show   m-alert m-alert--outline m-alert--air" role="alert"><button onclick="deleteweekActivity('+response.last_id+')" type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+response.activity+'</div>');
						
			}else{
					$.notify({
								title: '<strong>Error!</strong>',
								message:response.msg
							},{
								type: 'danger',
								z_index: 10000,
							});
			}
		}

	});
function get_weekly_activity(obj){
		if(obj.value!=0){	
	var activity_list='';	
		$.ajax({
			dataType:'json',
			url:'./getweeklyactivity',
			type:'post',
			data:{dep_id:obj.value},
			success:function(data){	
								
				if(data.length<=0){
					$('#weekly_activity_lists').html('<div class="text-center">No Activities</div>');
				}else{							
				for (var i = 0, len = data.length; i < len; i++) {
					activity_list +='<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="deleteweekActivity('+data[i].wa_id+')"></button>'+data[i].wa_activity+'</div>';
				}
				$('#weekly_activity_lists').html(activity_list);				
				}
			}
			
		});
}else{
	$('#weekly_activity_lists').html('Please select a department');
}
}
function deleteweekActivity(activity_id){
		$.ajax({
			dataType:'json',
			url:'./delete_weekly_Activity',
			type:'POST',
			data:{activity_id},
			success:function(data){	
							if(data==1){
					$.notify({
				title: '<strong>Updated!</strong>',
				message: 'Removed successfully.'
			},{
				type: 'success',
				z_index: 10000,
			});			
				}else{
			$.notify({
				title: '<strong>Error!</strong>',
				message: 'Something went wrong!'
			},{
				type: 'danger',
				z_index: 10000,
			});
				
				}
			}
		});
}
//weekly Activity  ends

//Start monthly activity
	function monthlyactivity(){
//		alert('hii');
			$.ajax({ 
			dataType:'json',
			url:'./viewdepts',
			success:function(data){
				dep_list ='';
				dep_list +='<select id="sel" class="form-control m-input"  onchange="get_monthly_activity(this)"   name="select_dept2" ><option value="0">Select a department</option>';
				for (var i = 0, len = data.length; i < len; i++) {
					
					dep_list +='<option value="'+data[i].dep_id+'">'+data[i].dep_name+'</option>'; 
				//console.log(data[i].name);
					
				}
				dep_list +='</select>';
				$('#show_dep_ma').html(dep_list);
     			$('#addmonthly').modal('show');      			
//				
			}
		});
		
	}

$('#addMA_form').ajaxForm({	
		dataType:'json',
		success: function(response,status,xhr,$form){
			
			if(response.status==1){
			
			$('#monthly_activity_lists').prepend('<div class="alert alert-dismissible fade show   m-alert m-alert--outline m-alert--air" role="alert"><button onclick="deletemonthActivity('+response.last_id+')" type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+response.activity+'</div>');
						
			}else{
					$.notify({
								title: '<strong>Error!</strong>',
								message:response.msg
							},{
								type: 'danger',
								z_index: 10000,
							});
			}
		}

	});

function get_monthly_activity(obj){
		if(obj.value!=0){	
			var activity_list='';	
				$.ajax({
					dataType:'json',
					url:'./getmonthlyactivity',
					type:'post',
					data:{dep_id:obj.value},
					success:function(data){	

						if(data.length<=0){
							$('#monthly_activity_lists').html('<div class="text-center">No Activities</div>');
						}else{							
						for (var i = 0, len = data.length; i < len; i++) {
							activity_list +='<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="deletemonthActivity('+data[i].ma_id+')"></button>'+data[i].ma_activity+'</div>';
						}
						$('#monthly_activity_lists').html(activity_list);				
						}
					}

				});
		}else{
			$('#monthly_activity_lists').html('Please select a department');
		}
}

function deletemonthActivity(activity_id){
		$.ajax({
			dataType:'json',
			url:'./delete_monthly_Activity',
			type:'POST',
			data:{activity_id},
			success:function(data){	
							if(data==1){
					$.notify({
				title: '<strong>Updated!</strong>',
				message: 'Removed successfully.'
			},{
				type: 'success',
				z_index: 10000,
			});			
				}else{
			$.notify({
				title: '<strong>Error!</strong>',
				message: 'Something went wrong!'
			},{
				type: 'danger',
				z_index: 10000,
			});
				
				}
			}
		});
}
//Close monthly activity

//View attendance details
function attendance_view(id){
	
	$.ajax({
		url:'./attendance_datas',
		type:'post',
		data:{user_id:id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-attendance').html(data);
			$('#month_user_id').val(id);
		}
	});

}
//Close view of atttendance detials

//start inventory

	function selectTeams(){
//		alert('hii');
			$.ajax({
			dataType:'json', 
			url:'../Inventory/viewteams',
			success:function(data){
//				alert(data);
//				console.log(data);
				team_list ='';
				team_list +='<select id="sel_team" class="form-control m-input" name="select_team2"><option value="0">Select Team</option>';
				for (var i = 0, len = data.length; i < len; i++) {
					
					team_list +='<option value="'+data[i].team_id+'">'+data[i].name+'</option>';
				//console.log(data[i].name);
					
				}
				team_list +='</select>';
				$('#show_teams').html(team_list);  
//     			$('#addJD').modal('show');      			
//				
			}
		});
		
	}


$('#add_inv').ajaxForm({
		dataType:'json', 
		success: function(response, status, xhr, $form){
//			alert(response.flag);
			console.log(response.flag);
				if(response.flag==1){	 							
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Inventory Added successfully"
					},{
						type: 'success',
						z_index: 10000,
					});
				}
			else{
					$.notify({
						title: '<strong>Error!</strong>',
						message:response.msg
					},{
						type: 'danger',
						z_index: 10000,
					});
			}
			
		}
	}); 

function get_inv_type(invtype){
	$.ajax({
			type:'post',
			dataType:'html', 
			data:{invtype},
			url:'../inventory/get_inv_types',
			success:function(data){  
				$('#inventory_list_'+invtype).html(data);
				$('table').DataTable( {
					responsive: true
				} );
			}
		});
	
	
} 
// close inventory

//Comment section in admin score
function add_comments(uid){	
//alert('hiii');
			var comments = $('#comments').val();
//			var depmnt= $('#sel').val();
			if(comments!=''){
//				alert(comments);
//				alert(uid);
				
				$.ajax({
					dataType:'json',
					url:'../add_comments_c',
					type:'post',
					data:{comments,uid},
					success:function(data){	
						comments = comments.replace(/\n/g,"<br>");
						$('#comment_lists').prepend('<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="deleteComments('+data.last_id+','+uid+')"></button><span style="float:right;color:red;">'+data.time+'</span><br/>'+comments+'</div>');
						
					}	
				    
				});
				$('#comment').val('');
			}
			else{
					$.notify({
						title: '<strong>Alert!</strong>',
						message:"Please Enter Work Report"
						},{
							type: 'danger',
							z_index: 10000,
					});
			}
}

function deleteComments(ins_id,uid){
//	alert(ins_id);
//	alert(uid);
	
	var result = confirm("Are you sure that you want to delete this comment from database?");
	if(result){
			
			$.ajax({
					dataType:'json',
					url:'../delete_Comments',
					type:'post',
					data:{ins_id,uid},
					success:function(data){	
						console.log(data);
						if(data.stat==1){
							$.notify({
								title: '<strong>Success!</strong>',
								message:"Comment deleted successfully"
							},{
								type: 'success',
								z_index: 10000,
							});
						}
						else{
							$.notify({
								title: '<strong>Error!</strong>',
								message:"Something went wrong"
							},{
								type: 'danger',
								z_index: 10000,
							});
						}
						
						
						
					}	
				});
	}
	
	
}

function getAllComm(uid){
//	alert(uid);
		$.ajax({
					dataType:'html',
					url:'../getAllComments',
					type:'post',
					data:{uid},
					success:function(data){	
						console.log(data);
						
						$('#viewComm').html(data);
					}	
			});
}

function deleteInventory(invId){
	var result = confirm("Are you sure that you want to delete this item?");
	if(result){
			$.ajax({
					dataType:'html',
					url:'inventoryDelete',
					type:'post',
					data:{invId},
					success:function(data){	
						$('#row'+invId).remove();
							$.notify({
								title: '<strong>Success!</strong>',
								message:"Deleted successfully"
							},{
								type: 'success',
								z_index: 10000,
							});
					}	
			});
	}
}

//notification admin side

function viewlinkedinnot(){
		$.ajax({
			dataType:'json',
			url:'./viewemployees',
			success:function(data){
				table ='';
				for (var i = 0, len = data.length; i < len; i++) {
					table +='<tr>';
					table +='<td>'+(i+1)+'</td><td>'+data[i].fullname+'</td>';
				//console.log(data[i].name);
					table +='<tr>';
				}
				$('#notifDiv').html(table);
				$('#viewlinkedinlist').modal('show');     
				
			}
		});
		
	}


//Test code for datalist
$('#emp_input').on('change',function(){
  
		var  id      = $('#emp_input').val();
	    var  value   = $(this).val();
	    var  user_id = $('#emps [value="' + value + '"]').data('value');
//        alert($('#emps [value="' + value + '"]').data('value'));
	    console.log(user_id);
		$.ajax({
		url:'./attendance_datas',
		type:'post',
		data:{user_id:user_id},
		success:function(data){
//			console.log(data);
			$('#new-div-attendance').html(data);
			$('#month_user_id').val(user_id);
		}
	});
})

//Test code for datalist

//Test code for datalist in view daily status activity
$('#emp_input_daily').on('change',function(){
//		var id       = $('#emp_input_daily').val();
	    var  value   = $(this).val();
	    var  user_id = $('#emps [value="' + value + '"]').data('value');
//      alert($('#emps [value="' + value + '"]').data('value'));
	    console.log(user_id);
		$.ajax({
		url:'./daily_datas',
		type:'post',
		data:{user_id:user_id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-dailystat').html(data);
			$('#month_user_id').val(user_id);
		}
	});
})

//Test code for datalist in view daily activity

//Test code for datalist in view weekly activity
$('#emp_input_weekly').on('change',function(){
  
//		var id       = $('#emp_input_weekly').val();
	    var  value   = $(this).val();
	    var  user_id = $('#emps [value="' + value + '"]').data('value');
//      alert($('#emps [value="' + value + '"]').data('value'));
	    console.log(user_id);
			$.ajax({
		url:'./weekly_datas',
		type:'post',
		data:{user_id:user_id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-weekstat').html(data);
			$('#month_user_id').val(user_id);
		}
	});
})

//Test code for datalist in view weekly activity

//Test code for datalist in view monthly activity
$('#emp_input_monthly').on('change',function(){
//		var id       = $('#emp_input_monthly').val();
	    var  value   = $(this).val();
	    var  user_id = $('#emps [value="' + value + '"]').data('value');
//      alert($('#emps [value="' + value + '"]').data('value'));
	    console.log(user_id);
		$.ajax({
		url:'./monthly_datas',
		type:'post',
		data:{user_id:user_id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-monthlystat').html(data);
			$('#month_user_id').val(user_id);
		}
	});
})

//Test code for datalist in view monthly activity


//starts History of daily reports 
$('#daily_history_form').on('submit', function(event)
		{
			event.preventDefault();
//			alert('hii');
			var dataString = $("#daily_history_form").serialize();
			var url="./daily_History_datas"
			$.ajax(
				{
					type:"POST",
					url:url,
					data:dataString,
					success:function(data)
					{
						
						console.log(data);
						var userid = $('#month_user_id').val();
						if(userid==''){
							$.notify({
								title: '<strong>Error!</strong>',
								message:"Please Select an Employee!"
							},{
								type: 'danger',
								z_index: 10000,
							});
//							$('#new-div-admin2').show().html(data); 
							$("#monthly_status_emp")[0].reset();
						}
						else{
//							alert(userid);
//							$('#new-div-admin2').show().html(data);
							$('#new-div-daily_history').html(data);
							$("#monthly_status_emp")[0].reset();
						}
					
					}
				});
				return false;
		})
//attendance stat ends

function daily_history(id){
	
	$.ajax({
		url:'./daily_History_datas',
		type:'post',
		data:{user_id:id},
		success:function(data){
			console.log(data);
//			$('#new-div-admin').show('html');
//			$('#new-div-admin2').show('html');
			$('#new-div-daily_history').html(data);
			$('#month_user_id').val(id);
		}
	});

}
//History of daily reports

//datalist in daily history starts
$('#emp_input').on('change',function(){
  
		var  id      = $('#emp_input').val();
	    var  value   = $(this).val();
	    var  user_id = $('#emps [value="' + value + '"]').data('value');
//        alert($('#emps [value="' + value + '"]').data('value'));
	    console.log(user_id);
		$.ajax({
		url:'./daily_History_datas',
		type:'post',
		data:{user_id:user_id},
		success:function(data){
//			console.log(data);
			$('#new-div-daily_history').html(data);
			$('#month_user_id').val(user_id);
		}
	});
})

//close datalist in daily history
 
    function add_project(){
  	 $('#add_projectroom').ajaxForm({
		dataType:'json', 
		success: function(response, status, xhr, $form){
                        if(response.stat == 1 ){	 	
//				$('#pending-apps').prepend(response.html);						
					$.notify({
						title: '<strong>Success!</strong>',
						message:response.msg
					},{
						type: 'success',
						z_index: 10000,
					});
					$('#add_projectroom').clearForm();
					$('#emp_input_dtflx').val(null); 
							
				}
			else{
					$.notify({
						title: '<strong>Error!</strong>',
						message:response.msg
					},{
						type: 'danger', 
						z_index: 10000,
					});
				}
                  
		}
	});
  	
  }


//get details for edit project room

function get_project_det(id){
	$.ajax({
		url:'get_project_details',
		type:'post',
		dataType:'json',
		data:{id},
		success:function(data){
//			console.log(data.project[0]);
//			console.log(data);
			console.log(data);
			$('#project_id').val(id);
			$('#pr_name').val(data.project[0]['pr_name']);
			$('#createdby').val(data.project[0]['pr_createdby']);
			$('#pro_desc').val(data.project[0]['pr_description']);
			var user_ids = data.user_ids;
			listEmployees(user_ids);
			// $('#emp_input_dtflx').val(data.user_ids);			
			// $('#emp_input_dtflx').val(data.users);
			/*$('#emp_input_dtflx').flexdatalist({
				 'valueProperty':'value',
				 'value': '123',
				 'name': 'name 3'
			});	*/

		}
	});
}

function listEmployees(user_ids){
	$.ajax({
		url:'getEmployees',
		type:'get',
		dataType:'json',
		success:function(data){
			
			var employees = data.employees;
			$(employees).each(function () {
				
				if(user_ids.includes(this.user_id) == true){
					$("#emp_input_dtflx").append('<option selected="" value="'+this.user_id+'">'+this.fullname+'</option>');
				}else{
					$("#emp_input_dtflx").append('<option value="'+this.user_id+'">'+this.fullname+'</option>');
				}
				
			});
		}
	});
}

function edit_project(){

  	 $('#edit_projectroom').ajaxForm({
		dataType:'json', 
		success: function(response, status, xhr, $form){
				console.log(response);
                        if(response.stat == 1 ){	 	
//				$('#pending-apps').prepend(response.html);						
					$.notify({
						title: '<strong>Success!</strong>',
						message:response.msg
					},{
						type: 'success',
						z_index: 10000,
					});
					$('#add_projectroom').clearForm();
					$('#emp_input_dtflx').val(''); 
					get_project_det(response.pr_id);
					//$(this).closest('form').find("input[type=text]").val("");
//					$(this).closest('form').find("input[type=text], textarea").val("");
							
							
				}
			else{
					$.notify({
						title: '<strong>Error!</strong>',
						message:response.msg
					},{
						type: 'danger', 
						z_index: 10000,
					});
				}
                  
		}
	});
  	
  }

function changeInterviewStatus(obj,type){
	interview_status = obj;
	if(type == "create"){ /** When status change in create  */
		if(interview_status == "offered" || interview_status == "joined"){
			$('#date_join').css('display','block');
		}
		else{
			$('#date_join').css('display','none');
		}
	}
	else{ /** When status change in update scheduler  */
		if(interview_status == "offered" || interview_status == "joined"){
			$('#date_join_updated').css('display','block');
		}
		else{
			$('#date_join_updated').css('display','none');
		}
	}
	
}






function search_interview(flagvalue){
	var type       = "default";
	if(flagvalue == 0){
		var start_date = '';
		var end_date   = '';
		type       = "default";
	}
	else{
		var start_date = $(".start_date").val();
		var end_date = $(".end_date").val();
		type       = "withdate";
	}
	
	$.ajax({
		type: 'POST',
		data: {start_date, end_date, type},
		dataType:'json',
		url:'./interview_list',
		success:function(result){
			if(result.status == true){
				// console.log(result);
				var data = result.data;
				$("#interview-list-container").removeClass('m--hide');
				$("#interview-list").html('');
				var interview_list = "";
				$(data).each(function(){
					
					interview_list += "<tr>";
					interview_list += "<td class='td-wd'>"+this.exam_date+"</td>";
					if(this.priority == 1){
						interview_list += "<td class='td-wd'><a href='javascript:;' onclick='view_interview_details("+this.id+")' class='m-widget24__change custom_red' data-toggle='modal'>"+this.candidate_name+"</a></td>";
					}
					else{
						interview_list += "<td class='td-wd'><a href='javascript:;' onclick='view_interview_details("+this.id+")' class='m-widget24__change' data-toggle='modal'>"+this.candidate_name+"</a></td>";
					}
					
					interview_list += "<td class='td-wd'>"+this.position+"</td>";
					interview_list += "<td class='td-wd'>"+this.current_salary+"</td>";
					interview_list += "<td class='td-wd'>"+this.expected_salary+"</td>";
					interview_list += "<td class='td-wd'>"+this.notice_period+"</td>";
				
					if(this.status == "offered"){
						if(this.joining_date != null){
							interview_list += "<td class='td-wd'>"+this.joining_date+"</td>";
						}
						else{
							interview_list += "<td class='td-wd'>"+this.status+"</td>";
						}
						// interview_list += "<td class='td-wd'>"+this.joining_date+"</td>";
					}
					else{
						interview_list += "<td class='td-wd'>"+this.status+"</td>";
					}
					interview_list += "</tr>";
				});

				$('#interview-list').html(interview_list);
			}else{
				$("#interview-list-container").addClass('m--hide');
				$("#interview-list").html('');
				$.notify({
					title: '<strong>Failed!</strong>',
					message:result.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}
	});

}

// function view_interview_details(interview_id){
// 	$('#edit_exam_modal').modal('show');
// 	$('#exam_list_model').modal('hide');

// 	$.ajax({
// 		type: 'POST',
// 		data: {interview_id},
// 		dataType:'json',
// 		url:'./get_candidate_details',
// 		success:function(result){
// 			if(result.status == true){
// 				var data = result.data;
// 				// console.log("something check: ",data);
// 				$("#candidate_id_hidden").val(data['id']);
// 				$("#candidate_name_updated").val(data['candidate_name']);
// 				$("#candidate_email_updated").val(data['candidate_email']);
// 				$("#candidate_phone_updated").val(data['candidate_phone']);
// 				$("#candidate_position_updated").val(data['position']);
// 				$("#notice_period_exam_updated").val(data['notice_period']);
// 				$("#expected_salary_updated").val(data['expected_salary']);
// 				$("#current_salary_updated").val(data['current_salary']);
// 				$("#m_datetimepicker_4_3_updated").val(data['exam_date']);
// 				$('#creator_updated').val(data['creator']);
// 				console.log(data['joining_date']);
// 				if(data['joining_date'] != null){
// 					$("#m_datepicker_joindate_updated").val(data['joining_date']);
// 				}
			
				
// 				examiner_ids = [];
// 				$(data['examiners_details']).each(function(index,value){
// 					examiner_ids.push(value.user_id); 
// 				});
				
// 				$('#examiner_updated').val(examiner_ids);
// 				$('#examiner_updated').trigger('change');
// 				$("#interview_status_updated").val(data['status']);
// 				$("#interview_mode_updated").val(data['mode']);
// 				if(data['priority'] == 1){
// 					// $("#interview_priority_updated").val("on");
// 					$('#interview_priority_updated').prop('checked', true);
// 				}
// 				else{
// 					$('#interview_priority_updated').prop('checked', false);
// 				}
			

				
// 				if(data['resume'] != null){
// 					// var a_html = "<span  ><a class='custom_red' href="+base_url+"/assets/resumes/"+data['resume']+" target='_blank'>View CV of "+data['candidate_name']+"</a></span>";
// 					var a_html = "<label>View CV : </label>  <span><a class='btn btn-danger btn-small' href="+base_url+"/assets/resumes/"+data['resume']+" target='_blank'><i class='fa fa-download' aria-hidden='true'></i></a></span>";
					
// 					// 
// 					$("#download_resume").html(a_html);
// 				}
// 				else{
// 					$("#download_resume").html("");
// 				}
				
// 				$("#comments_updated").val(data['comments']);
				
// 				if(data['status'] == "offered" || data['status'] == "joined"){
// 					$('#date_join_updated').css('display','block');
// 				}
// 				else{
// 					$('#date_join_updated').css('display','none');
// 				}
				
// 				var delete_span = "<a onclick='cancel_exam("+data['id']+")' class='btn btn-danger text-white'>Delete</a>";
// 				$("#delete_scheduled_interview").html(delete_span);


// 			}else{
// 				$.notify({
// 					title: '<strong>Failed!</strong>',
// 					message:result.message
// 				},{
// 					type: 'danger',
// 					z_index: 10000,
// 				});
// 			}
// 		}
// 	});

// }

// view candidate

function view_interview_details(interview_id){
	document.getElementById("update_interview").reset();
	$('#edit_exam_modal').modal('show');
	// $('#exam_list_model').modal('hide');

	$.ajax({
		type: 'POST',
		data: {interview_id},
		dataType:'json',
		url:'./get_candidate_details',
		success:function(result){
			
			if(result.status == true){
				var data = result.data;
				// console.log("something check: ",data);
				$("#candidate_id_hidden").val(data['id']);
				$("#candidate_name_updated").val(data['candidate_name']);
				$("#candidate_email_updated").val(data['candidate_email']);
				$("#candidate_phone_updated").val(data['candidate_phone']);
				$("#candidate_position_updated").val(data['position']);
				$("#notice_period_exam_updated").val(data['notice_period']);
				$("#expected_salary_updated").val(data['expected_salary']);
				$("#current_salary_updated").val(data['current_salary']);
				$("#m_datetimepicker_4_3_updated").val(data['exam_date']);
				$('#creator_updated').val(data['creator']);
				console.log(data['joining_date']);
				if(data['joining_date'] != null){
					$("#m_datepicker_joindate_updated").val(data['joining_date']);
				}
				
				
				examiner_ids = [];
				$(data['examiners_details']).each(function(index,value){
					examiner_ids.push(value.user_id); 
				});
				
				$('#examiner_updated').val(examiner_ids);
				$('#examiner_updated').trigger('change');
				$("#interview_status_updated").val(data['status']);
				$("#interview_mode_updated").val(data['mode']);
				if(data['priority'] == 1){
					// $("#interview_priority_updated").val("on");
					$('#interview_priority_updated').prop('checked', true);
				}
				else{
					$('#interview_priority_updated').prop('checked', false);
				}
			

				
				if(data['resume'] != null){
					// var a_html = "<span  ><a class='custom_red' href="+base_url+"/assets/resumes/"+data['resume']+" target='_blank'>View CV of "+data['candidate_name']+"</a></span>";
					var a_html = "<label>View CV : </label>  <span><a class='btn btn-danger btn-small' href="+base_url+"/assets/resumes/"+data['resume']+" target='_blank'><i class='fa fa-download' aria-hidden='true'></i></a></span>";
					
					// 
					$("#download_resume").html(a_html);
				}
				else{
					$("#download_resume").html("");
					$("#resume_attach_updated").val("");

				}
				if(data['comments']){
					$("#comments_updated").html(data['comments'].replace(/\n/g, "<br />"));
				}else{
					$("#comments_updated").html("No record!");
				}
				if(data['comment_array']){
					var allCommentsView =	"";
					var comment_array	=	data['comment_array'].reverse()
					comment_array.forEach(element => {
						allCommentsView +=`						
									<div class="form-control m-input">
										<p>`+element.comment.replace(/\n/g,"<br>")+`</p>
										<b class="text-info">`+element.name+` </b> | <small class="text-info">`+element.time+`</small>
									</div> <br >`;
					});
					$("#allCommentsView").html(allCommentsView);
				}else{
						$("#allCommentsView").html(`<div class="form-control m-input"> No Comments !</div>`);
				}

				if(data['status'] == "offered" || data['status'] == "joined"){
					$('#date_join_updated').css('display','block');
				}
				else{
					$('#date_join_updated').css('display','none');
				}
				
				var delete_span = "<a onclick='cancel_exam("+data['id']+")' class='btn btn-danger text-white'>Delete</a>";
				$("#delete_scheduled_interview").html(delete_span);


			}else{
				$.notify({
					title: '<strong>Failed!</strong>',
					message:result.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}
	});

}

$("#warning_level_updater").click(function(){
	var warning_level = $("#warning_level").val();
	var userid = $(this).attr("user_id");
	console.log(userid);

	$.ajax({
		type: 'POST',
		data: {warning_level, userid},
		dataType:'json',
		url:'../manage_warning',
		success:function(response){
			if(response.status == true){
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'success',
					z_index: 10000,
				});
				$("#warningModel").modal('toggle');
			}else{
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}

	})
})
/*$('#warning_user_form').ajaxForm({
  	dataType:'json',
		success: function(response, status, xhr, $form) {
			console.log(response);
			if(response.status == true){
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'success',
					z_index: 10000,
				});
				$("#warningModel").modal('toggle');
			}else{
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}
  });*/

  // Overtime reset admin side
 $("#overtime_reset").on("click",function(){
var wrk_id=$(this).data("id");
	$.ajax({
		type:"POST",
		dataType:'json',
		data:{wrk_id},
		url:"../overtime_reset",
		success:function(data){
			if(data.status == true){
				$('#overtime_hrs').html("0 hrs 0 min");
				$('#extra_hrs').html("0 hrs 0 min");
				$.notify({
					title: '<strong>Success!</strong>',
					message:data.message
				},{
					type: 'success',
					z_index: 10000,
				});
				$("#warningModel").modal('toggle');
			}else{
				$.notify({
					title: '<strong>Error!</strong>',
					message:data.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}

		}
	});
	
	 });
	


/**
 * send fcm notification 
 * @return {[type]} [description]
 */
function alert_fcm(){
	var token = localStorage.getItem('token');
	$.ajax({
		dataType:'json',
		url:'./send_alert_via_fcm/'+token,
		success:function(data){
			console.log(data);
		}
	});
}


// function cancel_exam(id){
// 	var result = confirm("Are you sure? You want to cancel this interview?");
// 	if(result == true){
// 		$.ajax({
// 			type: 'POST',
// 			data: {interview_id:id},
// 			dataType:'json',
// 			url:'./cancel_interview',
// 			success:function(result){
// 				console.log(result);
// 				search_interview();
// 			}
// 		});
// 	}
// }

// function cancel_exam(id){
// 	var result = confirm("Are you sure? You want to delete this interview?");
// 	if(result == true){
// 		$.ajax({
// 			type: 'POST',
// 			data: {interview_id:id},
// 			dataType:'json',
// 			url:'./cancel_interview',
// 			success:function(result){
// 				console.log(result);
// 				if(result.status == true){
// 					$.notify({
// 						title: '<strong>Success!</strong>',
// 						message:result.message
// 					},{
// 						type: 'success',
// 						z_index: 10000,
// 					});
// 				}
// 				else{
// 					$.notify({
// 						title: '<strong>Sorry!</strong>',
// 						message:result.message
// 					},{
// 						type: 'danger',
// 						z_index: 10000,
// 					});
// 				}
// 				$('#edit_exam_modal').modal('hide');
// 				$('#exam_list_model').modal('show');
// 				search_interview(0);
// 			}
// 		});
// 	}
// }

function get_notice_users(e, user_idsa=""){
	// alert(user_idsa);
	var notice_usertype = $("#notice_usertype").val();
	
	if(notice_usertype == ""){
		alert('Please select usertype to continue');
		return false;
	}

	if(notice_usertype == 'all'){
		$("#notice_user").html('');
		$("#notice_users_list_div").addClass('hide');
	}

	$.ajax({
		type: 'POST',
		data: {notice_usertype},
		dataType:'json',
		url:'./get_notice_users',
		success:function(result){
			if(result.status == true){
				$("#notice_users_list_div").removeClass('hide');
				$("#notice_user").html('');
				var data = result.data;
				if(result.userType == 'individual'){
					$("#notice_users_label").html('Employees');
					$(data).each(function(){
						if(user_idsa == ""){
							$("#notice_user").append('\
									<option value="'+this.user_id+'">'+this.fullname+'</option>\
								');
						}else{
							var position = jQuery.inArray( this.user_id, user_idsa );
							if(position >= 0){
								$("#notice_user").append('\
									<option selected="true" value="'+this.user_id+'">'+this.fullname+'</option>\
								');
							}else{
								$("#notice_user").append('\
									<option value="'+this.user_id+'">'+this.fullname+'</option>\
								');
							}
						}
					});
				}
				if(result.userType == 'team'){
					$("#notice_users_label").html('Teams');
					$(data).each(function(){
						if(user_idsa == ""){
							$("#notice_user").append('\
									<option value="'+this.team_id+'">'+this.name+'</option>\
								');
						}else{
							var position = jQuery.inArray( this.team_id, user_idsa );
							if(position >= 0){
								$("#notice_user").append('\
									<option selected="true" value="'+this.team_id+'">'+this.name+'</option>\
								');
							}else{
								$("#notice_user").append('\
									<option value="'+this.team_id+'">'+this.name+'</option>\
								');	
							}
						}
					});
				}
				if(result.userType == 'department'){
					$("#notice_users_label").html('Departments');
					$(data).each(function(){
						if(user_idsa == ""){
							$("#notice_user").append('\
									<option value="'+this.dep_id+'">'+this.dep_name+'</option>\
								');
						}else{
							var position = jQuery.inArray( this.dep_id, user_idsa );
							if(position >= 0){
								$("#notice_user").append('\
									<option selected="true" value="'+this.dep_id+'">'+this.dep_name+'</option>\
								');
							}else{
								$("#notice_user").append('\
									<option value="'+this.dep_id+'">'+this.dep_name+'</option>\
								');
							}
						}
					});
				}
			}else{
				$("#notice_users_list_div").addClass('hide');
				alert(result.message);
			}
		}
	});
}

$(document).on('change', '#notice_usertype', get_notice_users);

$(document).on('click', '#notice_board_btn', function(){
	var notice_text = $('.summernote').summernote('code');
	var notice_user = $("#notice_user").val();
	var notice_usertype = $("#notice_usertype").val();
	var notice_color = $("#notice_color").val();

	if(notice_usertype == ''){
		alert('Please select notice type to continue');
		return false
	}
	if(notice_text == ''){
		alert('Please enter something in the notice field');
		return false
	}

	$.ajax({
		type: 'POST',
		data: {notice_text,notice_user,notice_usertype,notice_color},
		dataType:'json',
		url:'./save_notice',
		success:function(response){
			if(response.status == true){
				$("#notice_board_modal").modal('toggle');
				$('.select2').select2();
				$("#notice_user").html('');
				$('#notice_usertype').prop('selectedIndex',0);
				$('#notice_color').prop('selectedIndex',0);
				$('.summernote').summernote('code', '');
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'success',
					z_index: 10000,
				});
			}else{
				$.notify({
					title: '<strong>Failed!</strong>',
					message:response.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}
	});
});

$(".summernote").summernote({
	height:200,
	focus: true,
    callbacks: {
        onImageUpload: function(files) {
            uploadImage(files[0]);
        }
    }
});

function uploadImage(image) {
    var data = new FormData();
    var url = base_url+'Discussion/upload_image';
    data.append("file", image);
    $.ajax({
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "post",
        success: function(url) {
            var image = $('<img>').attr('src', url);
            $('#notice_text').summernote("insertNode", image[0]);
        },
        error: function(data) {
            console.log(data);
        }
    });
}
/** Get datas of notices or announcements */
function get_notice_board_list(modal="show"){
	$.ajax({
		type: 'POST',
		dataType:'json',
		url:'./notice_boards_list',
		success:function(response){
			console.log(response);
			if(response.status == true){
				if(modal == "show"){
					$("#notice_board_list_modal").modal("toggle");
				}
				var data = response.data;
				if(data){
					var count = 1;
					$("#notice_board_tbody").html('');
					$(data).each(function(){
						console.log(this);
						var notice = this.notice;
						var user = this.fullname;
						
						if(this.type == 'team'){
							user = this.team_name;
						}else if(this.type == 'department'){
							user = this.dep_name;
						}else if(this.type == 'all'){
							user = 'All';
						}
						var type = "'"+this.type+"'";
						
						
						
						var delete_url = '<button class="btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--pill" onclick="delete_notice('+this.notice_id+', '+type+', '+this.id+')" style="float:right;"> &nbsp;<i class="la la-trash"></i></button>';

						var edit_url = '<button class="btn btn-outline-accent btn-sm m-btn m-btn--icon m-btn--pill" id="atag_id_12" data="Edit" style="float:right;margin-right: 2px;" onclick="get_notice_details('+this.notice_id+', '+type+')">Edit</button>';

						notice = notice.replace(/<\/?[^>]+(>|$)/g, "");
						$("#notice_board_tbody").append('\
								<tr>\
								<td>#'+count+'</td>\
								<td>'+notice.substr(0, 30)+'</td>\
								<td>'+this.type+'</td>\
								<td>'+this.notice_date+'</td>\
								<td>'+user+'</td>\
								<td>'+edit_url+' '+delete_url+'</td>\
								</tr>\
							');
						count++;
					});
				}
			}

			else{
				$("#notice_board_tbody").html("<p style='color:red;margin-top:20px;'> Sorry,there are no active announcements currently!</p>");
				$("#notice_board_list_modal").modal("toggle");
				
			}
		}
	});
}

function delete_notice(notice_id, type, id){
	var confirmation = confirm('Do you want to delete this notice?');
	if(confirmation == true){
		$.ajax({
			type: 'POST',
			data:{notice_id, type, id},
			dataType:'json',
			url:'./delete_notice',
			success:function(response){
				if(response.status == true){
					$.notify({
						title: '<strong>Success!</strong>',
						message:response.message
					},{
						type: 'success',
						z_index: 10000,
					});
					get_notice_board_list('close');
				}else{
					$.notify({
					title: '<strong>Success!</strong>',
						message:response.message
					},{
						type: 'success',
						z_index: 10000,
					});
				}
			}
		});
	}
}

function get_notice_details(notice_id, type){
	$.ajax({
		type: 'GET',
		dataType:'json',
		url:'./notice_board_details/'+notice_id+'/'+type,
		success:function(response){
			if(response.status == true){
				var data = response.data;
				var type = data.type;
				var color = data.color;
				var users_a = data.users_a;
				var notice = data.notice;

				$("#notice_id").val(notice_id);
				
				$("#notice_board_list_modal").modal('toggle');
				$('#notice_board_list_modal').on('hidden.bs.modal', function () {
				  $('#notice_board_modal').modal('toggle')
				})

				$("#notice_usertype").val(type);
				$("#notice_color").val(color);
				$('.summernote').summernote('code', notice);
				$("#notice_board_btn").addClass("hide");
				$("#notice_board_update_btn").removeClass("hide");
				// $("#notice_user").val(users_a);

				get_notice_users('e', users_a);

			}else{
				$.notify({
				title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'success',
					z_index: 10000,
				});
			}
		}
	});
}


/** Update notices or announcements */
$("#notice_board_update_btn").on('click', function(){
	var notice_text = $('.summernote').summernote('code');
	var notice_user = $("#notice_user").val();
	var notice_usertype = $("#notice_usertype").val();
	var notice_color = $("#notice_color").val();
	var notice_id = $("#notice_id").val();

	if(notice_usertype == ''){
		alert('Please select notice type to continue');
		return false
	}
	if(notice_text == ''){
		alert('Please enter something in the notice field');
		return false
	}

	$.ajax({
		type: 'POST',
		data: {notice_text,notice_user,notice_usertype,notice_color,notice_id},
		dataType:'json',
		url:'./update_notice_board',
		success:function(response){
			if(response.status == true){
				$("#notice_board_modal").modal('toggle');
				$('.select2').select2();
				$("#notice_user").html('');
				$('#notice_usertype').prop('selectedIndex',0);
				$('#notice_color').prop('selectedIndex',0);
				$('.summernote').summernote('code', '');

				$("#notice_board_btn").removeClass("hide");
				$("#notice_board_update_btn").addClass("hide");
				
				$.notify({
					title: '<strong>Success!</strong>',
					message:response.message
				},{
					type: 'success',
					z_index: 10000,
				});
			}else{
				$.notify({
					title: '<strong>Failed!</strong>',
					message:response.message
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
		}
	});
})


$('#addDesig').ajaxForm({
	dataType:'json', 
	success: function(response, status, xhr, $form) {
		
		if(response.status == true){
			$("#newdesignation_modal").modal('toggle');
			$.notify({
				title: '<strong>Success!</strong>',
				message:response.message
			},{
				type: 'success',
				z_index: 10000,
			});
		}else{
			$.notify({
				title: '<strong>Failed!</strong>',
				message:response.message
			},{
				type: 'danger',
				z_index: 10000,
			});
		}
	}
}); 

function view_designations(){
	// $("#viewdesignation_modal").modal("toggle");
	$.ajax({
		type: 'GET',
		dataType:'json',
		url:'./get_designation_list',
		success: function(result){
			console.log(result);
			if(result.status == true){
				$("#viewdesignation_modal").modal("toggle");
				var data = result.data;
				var count = 1;
				$(data).each(function(){
					/*$("#design_tbody")append('\
						<tr>\
						<td>'+count+'</td>\
						<td>'+this.designation+'</td>\
						<td>'+this.designation+'</td>\
						</tr>\
						');*/

					$("#design_tbody").append('\
						<tr>\
							<td>'+count+'</td>\
							<td id="desig_td_'+this.desg_id+'">'+this.designation+'</td>\
							<td><button class="btn btn-outline-accent btn-sm m-btn m-btn--icon m-btn--pill" id="atag_id_'+this.desg_id+'" data="Edit" style="float:right;margin-right: 2px;" onclick="edit_designation('+this.desg_id+',this);return false;">Edit</button> </td>\
						</tr>\
					');

					count++;
				})
			}
		}
	});
}

function edit_designation(design_id,obj){
	var a_val = $(obj).attr('data');
	if(a_val == "Edit"){
		$(obj).attr('data','Save');
		$(obj).text('Save');
		var text = $('#desig_td_'+design_id).html();
		console.log(text);
		$('#desig_td_'+design_id).html('<input value="'+text+'" name="designation_name"/>');
	}
	else{
		
		var designation = $('#desig_td_'+design_id+' input').val();

		$(obj).attr('data','Edit');
		$(obj).text('Edit');

		$.ajax({
				url:'./edit_designation',
				data:{design_id,designation},
				type:'POST',
				dataType:'json',
				success:function(data){ 
					if(data.status == true){
						$.notify({
							title: '<strong>Error!</strong>',
							message:data.message
						},{
							type: 'success',
							z_index: 10000,
						});
					}else{
						$.notify({
							title: '<strong>Success!</strong>',
							message:data.message
						},{
							type: 'danger',
							z_index: 10000,
						});
					}
					$('#desig_td_'+design_id).html(designation);	

				}
			});
		
	}
   
}