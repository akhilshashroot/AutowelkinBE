var ticket_total_count;
//get datas of checklists 
function get_checklists($id){
		
	$.ajax({
		url:'./get_check',
		type:'post',
		data:{user_id:$id},
		success:function(data){
		console.log();
			$('#checklist').html(data);
		}
	});	
}

//close getdatas of checklist 
//Task Assigner
function getTeamData(user_id){
		
	$.ajax({
		dataType:'json',
		type:"POST",
		url:base_url+'user/getTeamData',
		data:{user_id},
		success:function(data){
			if(data.length>0){
				$("#others_tasks").show();
				var html = "";
				html 	+= "<select name='user_id'class='form-control m-input m-input--square'>";
				data.forEach(element => {
					html +="<option value="+element.user_id+"> "+element.fullname+" </option>";
				});
				html 	+= "</select>";
				$("#assignUsers").html(html);
				
			}else{
				$("#others_tasks").hide();
			}
		}
	});

	$.ajax({
		dataType:'json',
		type:"POST",
		url:base_url+'user/getTaskList',
		data:{user_id},
		success:function(data){
			console.log(data);
			if(data.length>0){
				var status =  "<i class='fa fa-circle text-danger'></i>";
				var html = "";
				html 	+= `<div class='form-group m-form__group col-md-12'>

				<div class='m-section'>
				
					<div class='m-section__content'>
						<table class='table table-sm m-table m-table--head-bg-brand table-bordered'>
							<thead class='thead-inverse'>
								<tr>
									<th>Task</th>
									<th>Assignee</th>
									<th>Status</th>									
									<th>Deadline</th>
									<th width=50 >Attachments</th>
									<th>Details</th>
								</tr>
							</thead>
							<tbody>`;
				data.forEach(element => {
					if(element.status==1){
							status =  "<i class='fa fa-circle text-green'></i>";
					}else{
						status =  "<i class='fa fa-circle text-danger'></i>";
					}
					if(element.fullname=='null'){
						element.fullname='Anees T';
				}

				var task_attachment="";


				if(element.task_attachment != "" ){
					element.task_attachment.forEach(attachment => {
						task_attachment += " <a target='_blank' href='"+base_url+"assets/tasks_attachments/"+attachment+"' ><i class='fa fa-save text-success'></i></a>";
					});
				}

				// var task_attachment="";
				// 	if(element.task_attachment){
				// 		task_attachment="<a href='"+base_url+"assets/tasks_attachments/"+element.task_attachment+"' ><i class='fa fa-save text-success'></i></a>";
				// 	}
					html +=`
									<tr class='task_`+element.asgnmnt_id+`'>
										<th scope='row'><a href=javascript:; onclick="viewTaskDetails(`+element.asgnmnt_id+`,'`+element.fullname+`',0)">`+element.title+`</a></th>
										
										<td>`+element.fullname+`</td>
										<td>`+status+`</td>
										<td>`+element.date.split("-").reverse().join("-")+`</td>
										<td>`+task_attachment+`</td>
										<td><a href=javascript:; onclick="removeTask(`+element.asgnmnt_id+`)" style="margin-left: 15px;"><i class='fa fa-trash'></i></a></td>
									</tr>
									
							`;
					
				});
				html 	+= `</tbody>
				</table>
			</div>
		</div>						
	</div>`;
				$("#assignment_added").html(html);
				
			}else{
				$("#assignment_added").html("No tasks assigned!");
			}
		}
	});
	$.ajax({
		dataType:'json',
		type:"POST",
		url:base_url+'user/getOwnTaskList',
		data:{user_id},
		success:function(data){
			console.log(data);
			if(data.length>0){
				
				var assignment_review = `<div class=''>

				<div class='m-section'>
				
					<div class='m-section__content'>
						<table class='table table-sm m-table m-table--head-bg-brand table-bordered'>
							<thead class='thead-inverse'>
								<tr>
									<th>Task</th>
									<th>Deadline</th>
									<th>Assigner</th>
									<th width=50>Attachments</th>
								</tr>
							</thead>
							<tbody>`;
				var assignment_completed = `<div class=''>

				<div class='m-section'>
				
					<div class='m-section__content'>
						<table class='table table-sm m-table m-table--head-bg-brand table-bordered'>
							<thead class='thead-inverse'>
								<tr>
									<th>Task</th>
									<th>Deadline</th>
									<th>Assigner</th>
									<th>Attachments</th>
								</tr>
							</thead>
							<tbody>`;
				
				data.forEach(element => {
					if(element.creator_id==1){
						element.fullname='Anees T';
				}else if(element.creator_id==7){
					element.fullname='Muneer';
				}

				var task_attachment="";
				if(element.task_attachment != "" ){
					element.task_attachment.forEach(attachment => {
						task_attachment += " <a target='_blank' href='"+base_url+"assets/tasks_attachments/"+attachment+"' ><i class='fa fa-save text-success'></i></a>";
					});
				}
				// var task_attachment="";
				// 	if(element.task_attachment){
				// 		task_attachment="<a target='_blank' href='"+base_url+"assets/tasks_attachments/"+element.task_attachment+"' ><i class='fa fa-save text-success'></i></a>";
				// 	}
					if(element.status==1){
						assignment_completed +=
						`<tr>
							<th scope='row'><a href=javascript:; onclick="viewTaskDetails(`+element.asgnmnt_id+`,'`+element.fullname+`',1)">`+element.title+`<a/></th>
							<td>`+element.date.split("-").reverse().join("-")+`</td>
							<td>`+element.fullname+`</td>
							<td class="text-center">`+task_attachment+`</td>
						</tr>`;
					}else{
					assignment_review +=`<tr>
					<th scope='row'><a href=javascript:; onclick="viewTaskDetails(`+element.asgnmnt_id+`,'`+element.fullname+`',1)">`+element.title+`</a></th>
					<td>`+element.date.split("-").reverse().join("-")+`</td>
					<td>`+element.fullname+`</td>
					<td class="text-center">`+task_attachment+`</td>
					</tr>`;
					}
				});
				
				if(assignment_review ==""){
					$("#assignment_review").html("No records!");
				}else{
					$("#assignment_review").html(assignment_review);
				}
				if(assignment_completed ==""){
					$("#assignment_completed").html("No records!");
				}else{
					$("#assignment_completed").html(assignment_completed);
				}
				
				
			}else{
				$("#assignment_review").html("No records!");
				$("#assignment_completed").html("No records!");
			}
		}
	});

}
function viewTaskDetails(asgnmnt_id,fullname,flag){


$.ajax({
	dataType:'json',
	type:"POST",
	url:base_url+'user/viewTaskDetails',
	data:{asgnmnt_id},
	success:function(data){
		$("#task_title").html(data.title);
		if(fullname =='null'){
			fullname = "Anees T";
		}
		if(flag==1){
			$("#assigned_to").html(data.name);
			$("#assigner").html(fullname);
		}else{
			$("#assigned_to").html(fullname);
			$("#assigner").html(data.fullname);

		}
		$('#task_checkbox').prop('checked', false);
		if(data.status=='Done'){
			
			$('#task_checkbox').prop('checked', true);
		}
		$("#assigned_date").html(data.realDate);
		$("#task_details").html(data.body);
		$("#table_task_title").html(data.title);
		$("#task_status").html(data.status);
		$("#task_id").val(data.asgnmnt_id);
		$("#task_deadline").html(data.date);
		
		var task_attachment = "";
		if(data.task_attachment != ""){
			data.task_attachment.forEach(attachment => {
				task_attachment += " <a target='_blank' href='"+base_url+"assets/tasks_attachments/"+attachment+"' ><i class='fa fa-save text-success'></i></a>";
			});	
		}
		$("#task_attachments").html(task_attachment);

		
		var deComments = "";
		if(data.comments.length>0){
		data.comments.forEach(element => {
			conversation=element.comments.replace(/\n/g,"<br/>");
			 deComments += "<p><br/>"+element.name+"  <span class='m--font-danger' style='float:right'>"+element.date+"</span><br> "+conversation +"  </p><hr>";
		});
	}else{
		deComments += "no comments!";
	 }
		$("#task_comments").html(deComments);
		$("#othrtasks").modal("show");
	

	}
});

}
function selectPeriod(value){
var select = "";
switch (value) {
	case "ONE": select  += `<div class='form-group m-form__group row col-md-12'><div class='form-group m-form__group row col-md-3'>
	<label class='col-form-label col-lg-3 col-sm-12'>
	Select Deadline
	</label>
	</div>
	<div class='form-group m-form__group col-md-5 col-sm-12'><input type='date' name='date' class='form-control m-input' value='' placeholder='dd/mm/yyyy' /> </div>
	</div> `;
		break;
		
	case "Weekly" : select  += `<select name='day' class='form-control m-input m-input--square'>
	<option value="0">Sunday</option>
	<option value="1">Monday</option>
	<option value="2">Tuesday</option>
	<option value="3">Wednesday</option>
	<option value="4">Thursday</option>
	<option value="5">Friday</option>
	<option value="6">Saturday</option>
	</select> `;
		break;
		
	default: select  += " ";
		break;
}

$("#datePick").html(select);
}

$('#add_task').ajaxForm({
dataType:'json', 
success: function(response, status, xhr, $form) {
	$('#add_task').clearForm();	
	if(response.status==0){								
$.notify({
		title: '<strong>Success!</strong>',
		message:"Task added  successfully"
	},{
		type: 'success',
		z_index: 10000,
	});
	}else{
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

$('#update_task_comment').ajaxForm({
dataType:'json', 
success: function(response, status, xhr, $form) {
	if(response.status){								
$.notify({
		title: '<strong>Success!</strong>',
		message:response.message
	},{
		type: 'success',
		z_index: 10000,
	});
	$('#task_comments').append(`<p><br>`+response.comment.name+`<span class='m--font-danger' style='float:right'>`+response.comment.date+`</span><br> `+response.comment.comments+`  </p><hr>`);
	$('#text-comments').val('');

	}else{
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


//Task Assigner


//Task Delete
function removeTask(asgnmnt_id) {

	if (confirm("Are you sure you want to delete the task?") == true) {
			$.ajax({
					dataType: 'json',
					type: "POST",
					url:'/User/deleteTask',
					data: { asgnmnt_id},
					success: function (data) {
							if(data.status){							
					$.notify({
									title: '<strong>Success!</strong>',
									message:data.message
							},{
									type: 'success',
									z_index: 10000,
							});
							$(".task_"+asgnmnt_id).fadeOut();
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

	}
}

// Task Delete End

//getting daily activities & weekly activities
function get_daily_acts($user_id){
	$.ajax({
		url:'./get_daily_activities',
		type:'post',
		data:{user_id:$user_id},
		success:function(data){
			$('#daily_act_list').html(data);
			get_workreport();
		}
	});	
get_checklists($user_id);	
}

//ends getting daily acts
$( document ).ready(function() {
view_current_month_score();
	});

function view_current_month_score(){
//	alert('View current score');
$.ajax({
		url:'./month_picker',
		type:'post',
		success:function(data){
			console.log(data);
			$('#new-div').show().html(data);
		}
	});
}

//status 
function alter_checklist_stat($uid,$act_id){
		 $.ajax({
		url:'./alter_Status',
		type:'post',
		data:{status:1,user_id:$uid,act_id:$act_id},
		success:function(data){
			$('#status_btn_'+$act_id).attr('disabled',true);		
			$('#check_btn_'+$act_id).removeClass('fa fa-times');
			$('#check_btn_'+$act_id).addClass('fa fa-check');
		}
		
	});	
}
//close status
var daily_activity_flag = true;
//getting daily activities
function alter_daily_stat(daily_act_id,att_id){

	var  daily_input = document.getElementById("daily_input_"+daily_act_id);	
	/**
	 * retrieving ticket details from the user
	 */
	window.GLOBAL_act_id=daily_act_id;
	switch(daily_act_id){
		case 599:
		case 691:
		case 703:
		case 711:
			if( daily_activity_flag == false){
				return false;
			}
		
			daily_activity_flag = false;
			var ticket = $("#daily_input_"+daily_act_id).val();
			ticket = parseInt(ticket);
			ticket++;
			$("#daily_input_"+daily_act_id).val(ticket);
			var ticket_form = open_ticket_modal(ticket);
			if(ticket_form == false){
				return false;
			}
			break;

		case 600:
		case 601:
			var daily_acti_id = 600;
			for(var i=0; i<=1; i++){
				update_ticket_count(daily_acti_id,att_id);
				daily_acti_id++;
			}
		break;
		case 693:
		case 694:
		update_ticket_count(693,att_id);
		update_ticket_count(694,att_id);
			break;

			case 702:
			case 701:
			update_ticket_count(702,att_id);
			update_ticket_count(701,att_id);
				break;
			case 709:
			case 710:
			update_ticket_count(709,att_id);
			update_ticket_count(710,att_id);
				break;
		default:
			update_ticket_count(daily_act_id,att_id);
			break;
	}			

}


function update_ticket_count(daily_act_id,att_id){
var  daily_input = document.getElementById("daily_input_"+daily_act_id);
if(daily_input){
		 var daily_inputValue= daily_input.value;
	 }else{
		 var daily_inputValue= 1;
 }

 $.ajax({
	url:'./alter_daily_status',
	type:'post',
	dataType:"json",
	data:{daily_act_id:daily_act_id,daily_inputValue:daily_inputValue},
	success:function(data){
		if(data.status==1){
			$('#daily_btn_'+daily_act_id).removeClass('fa fa-times');
			$('#daily_btn_'+daily_act_id).addClass('fa fa-check');
			$.notify({
					title: '<strong>Success!</strong>',
					message:"Successfully Saved "
				},{
					type: 'success',
					z_index: 10000,
				});	
			
		}else{				
			$.notify({
				title: '<strong>Alert!</strong>',
				message:"Something went wrong.Please try again"
				},{
					type: 'danger',
					z_index: 10000,
			});	
		}				
		// $(daily_input).attr('disabled',true);		
	}			
});
}


function open_ticket_modal(ticket_count){



$.ajax({
	url:'./get_ticket_count',
	type:'get',
	dataType: 'json',
	success:function(result){
		console.log(result)
		if(result.status == true){
			var total_count = parseInt(result.total_count);
			var count = ticket_count-total_count;
			console.log(count);
			ticket_total_count = count;
			$("#ticket_modal_body").html('');
			if(count > 0){
				console.log(count);
				$("#ticket_details_div").removeClass("hide");
				$("#ticket_details_div").html("");
				for(var i=1; i<=1; i++){
					$("#ticket_details_div").append('\
							<div class="row">\
								<div class="col-md-10">\
									<div class="form-group">\
										<label>Ticket URL</label>\
										<input type="text" class="form-control" id="ticket_id_'+i+'">\
									</div>\
								</div>\
								<div class="col-md-2">\
									<div class="form-group">\
										<label>Initial Response Time</label>\
										<select id="sla_'+i+'" class="form-control">'+get_sla_options()+'</select>\
									</div>\
								</div>\
							</div>\
							<div class="row">\
								<div class="col-md-10">\
									<div class="form-group">\
										<label>Ticket Response</label>\
										<textarea id="response_'+i+'" class="form-control" rows="3"></textarea>\
									</div>\
								</div>\
								<div class="col-md-2">\
									<div class="form-group" style="margin-top: 2rem;">\
										<label>&nbsp;</label><br>\
										<button type="button"  id="ticket_details_submit_btn" class="btn btn-md btn-success text-right" >\
											Save\
										</button>\
										<button type="button" id="ticket_details_cancel_btn" class="btn btn-md btn-danger text-right" >\
											X\
										</button>\
									</div>\
								</div>\
							</div>\
						')
					/*$("#ticket_modal_body").append('\
							<tr>\
								<td>\
									<div class="form-group">\
										<input id="ticket_id_'+i+'" type="text" class="form-control" name="">\
									</div>\
								</td>\
								<td>\
									<div class="form-group">\
										<textarea id="response_'+i+'" class="form-control" rows="1"></textarea>\
									</div>\
								</td>\
								<td>\
									<div class="form-group">\
										<select id="sla_'+i+'" class="form-control">'+get_sla_options()+'</select>\
									</div>\
								</td>\
							</tr>\
						');*/
				}
				
			}
		}
	}
});


// $("#ticket_updating_modal").modal("toggle");
return false;
}

$(document).on('click', '#ticket_details_cancel_btn', function(){
var daily_act_id = window.GLOBAL_act_id;
if(!daily_act_id){
	$.notify({
		title: '<strong>Alert!</strong>',
		message:"Something went wrong.Please try again"
		},{
			type: 'danger',
			z_index: 10000,
	});	
	daily_activity_flag = true;
	return;
}
$("#ticket_details_div").addClass("hide");
$("#ticket_details_div").html('');
var daily_input = $("#daily_input_"+daily_act_id).val();
//console.log(daily_input);
daily_input = parseInt(daily_input);
daily_input--;
$("#daily_input_"+daily_act_id).val(daily_input);
daily_activity_flag = true;
})

$(document).on("click", "#ticket_details_submit_btn", function(){

var form_a = [];
for(var i=1; i<=ticket_total_count; i++){

	var ticket_id = $("#ticket_id_"+i).val();
	var response = $("#response_"+i).val();
	var sla = $("#sla_"+i).val();

	if(ticket_id == ""){
		alert('Please enter ticket url');
		return false;
	}

	if(response == ""){
		alert('Please enter ticket response');
		return false;
	}

	if(sla == "0 min"){
		alert('Please enter response time');
		return false;
	}

	var insert_a = {
		ticket_id: $("#ticket_id_"+i).val(),
		response: $("#response_"+i).val(),
		sla: $("#sla_"+i).val(),
	}
	form_a.push(insert_a);
}

$("#ticket_details_submit_btn").attr('disabled', true);
var daily_act_id = window.GLOBAL_act_id;
if(!daily_act_id){
	$.notify({
		title: '<strong>Alert!</strong>',
		message:"Something went wrong.Please try again"
		},{
			type: 'danger',
			z_index: 10000,
	});	
	daily_activity_flag = true;
	return;
}
var daily_inputValue = $("#daily_input_"+daily_act_id).val();

$.ajax({
	url:'./alter_daily_status',
	type:'post',
	dataType:"json",
	data:{daily_act_id:daily_act_id,daily_inputValue:daily_inputValue, ticket_details_a: form_a},
	success:function(data){
		if(data.status==1){
			$("#ticket_details_div").addClass("hide");
			$("#ticket_details_div").html("");
			$('#daily_btn_'+daily_act_id).removeClass('fa fa-times');
			$('#daily_btn_'+daily_act_id).addClass('fa fa-check');
			$.notify({
					title: '<strong>Success!</strong>',
					message:"Successfully Saved "
				},{
					type: 'success',
					z_index: 10000,
				});	
			daily_activity_flag = true;
			get_saved_ticket_details();
			
		}else{				
			$.notify({
				title: '<strong>Alert!</strong>',
				message:"Something went wrong.Please try again"
				},{
					type: 'danger',
					z_index: 10000,
			});	
			daily_activity_flag = true;
		}				
	}			
});	
});

function get_saved_ticket_details(){
$.ajax({
	dataType:'html',
	url:'./get_saved_ticket_details',
	success:function(data){
	$('#submited_ticket_details').html(data);	 
	}
});
}
var response_ticket_id = 0;
function view_ticket_details_edit(ticket_id){
response_ticket_id = ticket_id;
$.ajax({
	type: 'POST',
	data: {ticket_id},
	url: './get_ticket_response',
	dataType: 'json',
	success: function (result){
		if(result.status == true){
			var data = result.data;
			$("#ticket_response_details").val(data.response);
			$("#ticket_edit_model").modal("toggle");
		}
	}
})

}

$("#ticekt_respnse_update_btn").on('click', function(){
var response = $("#ticket_response_details").val();
var ticket_id = response_ticket_id;
$.ajax({
	type: 'POST',
	url: './update_ticket_response',
	data: {response, ticket_id},
	dataType: 'json',
	success: function(result){
		if(result.status == true){
			get_saved_ticket_details();
			$("#ticket_edit_model").modal("toggle");
		}
		// console.log(result);
	}
})
});
/*$("#ticket_details_submit_btn").click(function(){
alert();
// $("#ticket_modal_form").
console.log(ticket_total_count);
var form_a = [];
for(var i=1; i<=ticket_total_count; i++){
	var insert_a = {
		ticket_id: $("#ticket_id_"+i).val(),
		response: $("#response_"+i).val(),
		sla: $("#sla_"+i).val(),
	}
	form_a.push(insert_a);
}

var daily_inputValue = $("#daily_input_599").val();
var daily_act_id = 599;
$.ajax({
	url:'./alter_daily_status',
	type:'post',
	data:{daily_act_id:daily_act_id,daily_inputValue:daily_inputValue, ticket_details_a: form_a},
	success:function(data){
		if(data==1){
			$("#ticket_updating_modal").modal("toggle");
			$('#daily_btn_'+daily_act_id).removeClass('fa fa-times');
			$('#daily_btn_'+daily_act_id).addClass('fa fa-check');
			$.notify({
					title: '<strong>Success!</strong>',
					message:"Successfully Saved "
				},{
					type: 'success',
					z_index: 10000,
				});	
			
		}else{				
			$.notify({
				title: '<strong>Alert!</strong>',
				message:"Something went wrong.Please try again"
				},{
					type: 'danger',
					z_index: 10000,
			});	
		}				
	}			
});	


});*/

function get_sla_options(){
return '<option value="0 min">Select</option>\
		<option value="0 - 5 min">0 - 5 min</option>\
		<option value="5 - 10 min">5 - 10 min</option>\
		<option value="10 - 15 min">10 - 15 min</option>\
		<option value="15 - 20 min">15 - 20 min</option>\
		<option value="20 - 25 min">20 - 25 min</option>\
		<option value="25 - 30 min">25 - 30 min</option>\
		<option value="30 - 35 min">30 - 35 min</option>\
		<option value="35 - 40 min">35 - 40 min</option>\
		<option value="40 - 45 min">40 - 45 min</option>\
		<option value="45 - 50 min">45 - 50 min</option>\
		<option value="50 - 55 min">50 - 55 min</option>\
		<option value="55 - 60 min">55 - 60 min</option>\
		<option value="above 1 hour">above 1 hour</option>\
	';
}


//ends getting daily acts
//No: Tickets Done
function no_tickets_h(){
 var ticket_handled= $('#ticket_handled').val();
	 $.ajax({
//			dataType:'json',
		url:'./Save_Tickets_done',
		type:'post',
		data:{ticket_handled},
		success:function(data){
		console.log(data);
			if(ticket_handled==''){
				
//					alert(data);
				$.notify({
					title: '<strong>Alert!</strong>',
					message:"Please Enter Number of Ticket_handled"
					},{
						type: 'danger',
						z_index: 10000,
				});
			}
			else{
				if(data==2){
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Successfully Added No: Of Ticket_handled"
					},{
						type: 'success',
						z_index: 10000,
					});	

				}
				
			}
			$('#ticket_handled').val('');
//				$("#tickets_done")[0].reset();
		}
		
	});	
	

}
//Close No Tickets  Done
function no_tickets_r(){
 var ticket_resolved= $('#tickets_resolved').val();
	 $.ajax({
//			dataType:'json',
		url:'./Save_Tickets_resolved',
		type:'post',
		data:{ticket_resolved},
		success:function(data){
		console.log(data);
			if(ticket_resolved==''){
				
//					alert(data);
				$.notify({
					title: '<strong>Alert!</strong>',
					message:"Please Enter Number of Ticket_Resolved"
					},{
						type: 'danger',
						z_index: 10000,
				});
			}
			else{
				if(data==2){
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Successfully Added No: Of Ticket_Resolved"
					},{
						type: 'success',
						z_index: 10000,
					});	

				}
				
			}
			$('#tickets_resolved').val('');
//				$("#tickets_done")[0].reset();
		}
		
	});	
	

}
//No: Tickets Pending
function no_tickets_p(){
//	 alert('Hii');
 var Tickets_pending= $('#tickets_pending').val();
	 $.ajax({
//			dataType:'json',
		url:'./Save_Tickets_Pending',
		type:'post',
		data:{tickets_pending:Tickets_pending},
		success:function(data){
		console.log(data);
			if(Tickets_pending==''){
				
//					alert(data);
				$.notify({
					title: '<strong>Alert!</strong>',
					message:"Please Enter Number of Tickets Pending"
					},{
						type: 'danger',
						z_index: 10000,
				});
			}
			else{
				if(data==2){
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Successfully Added No: Of Tickets Pending"
					},{
						type: 'success',
						z_index: 10000,
					});	

				}
				
			}
			$('#tickets_pending').val('');
//				$("#tickets_done")[0].reset();
		}
		
	});	
	

}
//Close No Tickets  Pending

function add_workreport(){	
//alert('hiii');
		var work_report= $('#work_report').val();
//			var depmnt= $('#sel').val();
		if(work_report!=''){
//				alert('hiii');

				$.ajax({
				dataType:'json',
				url:'./add_work_report',
				type:'post',
				data:{work_reports:work_report},
				success:function(data){	
//					alert(data.last_ins_id);
				work_report = work_report.replace(/\n/g,"<br>");
					$('#work_lists').prepend('<div style="text-overflow: ellipsis; white-space: normal; word-break:break-all;" class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"  onclick="deleteWorkReport('+data.last_ins_id+')" ></button><span style="float:right;color:red;">'+data.time+'</span><br/>'+work_report+'</div>');
				}	
					
			});
			$('#work_report').val('');
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


function deleteWorkReport(workreport_id){
//alert(workreport_id);
	$.ajax({
		
		url:'./delete_work_report',
		type:'POST',
		data:{workreport_id},
		success:function(data){	
			if(data==1){
				$.notify({
			title: '<strong>Success!</strong>',
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
//close test

//Work report
function get_workreport(){
//	alert('welcome to work report');
var workreport='';
	$.ajax({
		url:'./get_work_report',
		type:'POST',
		dataType:'json',
		success:function(data){	
		if(data.length>0){
			for(var i=0;i<data.length;i++){
				
				data[i].workreport = (data[i].workreport).replace(/\n/g,"<br>");
				$('#work_lists').append('<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"  onclick="deleteWorkReport('+data[i].workreport_id+')"></button><span style="float:right;color:red;">'+data[i].time+'</span><br/>'+data[i].workreport+'</div>');
			}
			}
			

		}
	});
}
//Close work report

//Start view all reports
function view_all_workreports(){
alert('hiii');
$.ajax({
		
		url:'./View_all_workreports',
		type:'POST',
		dataType:'json',
		success:function(data){	
//				alert(data);
//				alert(data[0].workreport);
//				alert(data[0].time);
//				alert(data.length);
			for(var i=0;i<=data.length;i++){
				data[i].workreport = data[i].workreport.replace(/\n/g,"<br>");
				$('#work_lists').append('<div class="alert alert-dismissible fade show m-alert m-alert--outline m-alert--air" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span style="float:right;color:red;">'+data[i].time+'</span><br/>'+data[i].workreport+'</div>');
			}
			

		}
	});
}

//Close view all reports

//close add activity

//Close user report
//USer side work reports
//Teamwise workreport and tasks
function insert_tasks(label){
//	alert(label);
 var task_label=label;
 var task_list= $('#'+label).val();
	if(task_list==''){
				
//					alert(data);
				$.notify({
					title: '<strong>Alert!</strong>',
					message:"Fill the field"
					},{
						type: 'danger',
						z_index: 10000,
				});
			}
		else{
		$.ajax({
//		dataType:'json',
		url:'./save_tasks',
		type:'post',
		data:{task_label,task_list},
		success:function(data){
		console.log(data);
//					alert(task_label);
//					alert(task_list);
			/////
			
				if(data==1){
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Successfully Saved"
					},{
						type: 'success',
						z_index: 10000,
					});	
					
				}
				
//				$('#'+task_label).val('');
			$('#'+label).val('');
//				$('#ticket_handled').val('');
//				$("#tickets_done")[0].reset();
		}
		
	});	
}
 
	

}
function updateweekly(wa_id){
var  weekly_input= document.getElementById("weekly_input_"+wa_id);

//confirmation
var result = confirm("Are you sure you want to update this weekly activity?");

				if (result) {


	if(weekly_input){
			var weekly_inputValue= weekly_input.value;
	}else{
	 var weekly_inputValue= 1;
}

		$.ajax({
//			dataType:'json',
		url:'./update_weekly',
		type:'post',
		data:{wa_id,weekly_inputValue},
		success:function(data){
		console.log(data);

				if(data==1){
						
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Successfully Saved"
					},{
						type: 'success',
						z_index: 10000,
					});	
//						$('#weekly_act_'+wa_id+' i').toggleClass('fa-times fa-check');
//						$('#weekly_act_'+wa_id+' button').attr('disabled','true');
//						$(weekly_input).attr('disabled','true');
					$('#weekly_act_btn'+wa_id).removeClass('fa fa-times');
					$('#weekly_act_btn'+wa_id).addClass('fa fa-check');
				} 

		}
		
	});	
					
				}//end update confirmation
}


//start monthly activity status updation
function updatemonthly(mid){
var  monthly_input= document.getElementById("monthly_input_"+mid);

//start update confirmation
var result = confirm("Are you sure you want to update this monthly activity?");

if (result) {
					
					
	if(monthly_input){
			var monthly_inputValue= monthly_input.value;
	}
else
{
	 var monthly_inputValue = 1;
	}
	
		$.ajax({
//			dataType:'json',
		url:'./checkrow_in_monthlydata',
		type:'post',
		data:{monthly_inputValue,mid},
		success:function(data){
		console.log(data);
			if(data==1){

				$.notify({
					title: '<strong>Success!</strong>',
					message:"Successfully Saved"
				},{
					type: 'success',
					z_index: 10000,
				});	
				
				$('#m_stat_btn_'+mid).removeClass('fa fa-times');
				$('#m_stat_btn_'+mid).addClass('fa fa-check');
//					$('#m_stat_btn_'+mid).toggleClass('fa fa-check fa fa-times');
//					$('#m_stat_btn_'+wa_id+' i').toggleClass('fa-times fa-check');
//						$('#weekly_act_'+wa_id+' button').attr('disabled','true');
//						$(weekly_input).attr('disabled','true');
			} 

		}
		
	});

	}// end monthly confirmation
	
}


//Close monthly activity status updation
//start assigned activity statuses
function update_as_act(last_act_id){
//	alert(last_act_id);
var  act_input= document.getElementById("act_input_"+last_act_id);
	if(act_input){
			var act_inputValue= act_input.value;
	}else{
	 var act_inputValue= 1;
}

		$.ajax({
//			dataType:'json',
		url:'./update_assigned_Act_stat',
		type:'post',
		data:{last_act_id,act_inputValue},
		success:function(data){
		console.log(data);
//					alert(task_label);

		
				if(data==1){
						
					$.notify({
						title: '<strong>Success!</strong>',
						message:"Successfully Saved"
					},{
						type: 'success',
						z_index: 10000,
					});	
//						$('#weekly_act_'+wa_id+' i').toggleClass('fa-times fa-check');
					$('#as_act_btn'+last_act_id).attr('disabled','true');
					$('#act_input_'+last_act_id).attr('disabled','true');
				} 

		}
		
	});	
}

//Close assigned activity statuses
//Close teamwise workreport and tasks
function display_hrs(){
	$.ajax({
	dataType:'json',
	url:'./Pending_working',
	type:'post',
	success:function(data){
	}
});
}
 $('#addnotification').ajaxForm({
	dataType:'json', 
	success: function(response, status, xhr, $form)  {
		 if(response.not_status==1){
			 $.notify({
						title: '<strong>Success!</strong>',
						message:"You've been Added to the list"
					},{
						type: 'success',
						z_index: 10000,
					});
		 }
		$('#newnotification').modal('hide');
	}
}); 

function skillStatusUpdater(skill_id){
$.ajax({
	dataType:'json',
	url:'./skillStatusUpdater',
	type:'post',
	data:{skill_id},
	success:function(response){
		if(response.status==1){
			$.notify({
				title: '<strong>Updates!</strong>',
				message:"Successfully requested for review. "
			},{
				type: 'info',
				z_index: 10000,
			});
			$(".skill"+skill_id).removeAttr("onclick").html('<i class="fa fa-check"></i>').attr("disabled",true);
		}else{
			$.notify({
				title: '<strong>Error!</strong>',
				message:"Please try again. "
			},{
				type: 'danger',
				z_index: 10000,
			});
		}
	}
});
}







function retry(isDone, next) {
var current_trial = 0,
	max_retry = 50,
	interval = 10,
	is_timeout = false;
var id = window.setInterval(
	function() {
		if (isDone()) {
			window.clearInterval(id);
			next(is_timeout);
		}
		if (current_trial++ > max_retry) {
			window.clearInterval(id);
			is_timeout = true;
			next(is_timeout);
		}
	},
	10
);
}

function isIE10OrLater(user_agent) {
var ua = user_agent.toLowerCase();
if (ua.indexOf('msie') === 0 && ua.indexOf('trident') === 0) {
	return false;
}
var match = /(?:msie|rv:)\s?([\d\.]+)/.exec(ua);
if (match && parseInt(match[1], 10) >= 10) {
	return true;
}
return false;
}

function detectPrivateMode(callback) {
var is_private;

if (window.webkitRequestFileSystem) {
	window.webkitRequestFileSystem(
		window.TEMPORARY, 1,
		function() {
			is_private = false;
		},
		function(e) {
			console.log(e);
			is_private = true;
		}
	);
} else if (window.indexedDB && /Firefox/.test(window.navigator.userAgent)) {
	var db;
	try {
		db = window.indexedDB.open('test');
	} catch (e) {
		is_private = true;
	}

	if (typeof is_private === 'undefined') {
		retry(
			function isDone() {
				return db.readyState === 'done' ? true : false;
			},
			function next(is_timeout) {
				if (!is_timeout) {
					is_private = db.result ? false : true;
				}
			}
		);
	}
} else if (isIE10OrLater(window.navigator.userAgent)) {
	is_private = false;
	try {
		if (!window.indexedDB) {
			is_private = true;
		}
	} catch (e) {
		is_private = true;
	}
} else if (window.localStorage && /Safari/.test(window.navigator.userAgent)) {
	try {
		window.localStorage.setItem('test', 1);
	} catch (e) {
		is_private = true;
	}

	if (typeof is_private === 'undefined') {
		is_private = false;
		window.localStorage.removeItem('test');
	}
}

retry(
	function isDone() {
		return typeof is_private !== 'undefined' ? true : false;
	},
	function next(is_timeout) {
		callback(is_private);
	}
);
}

detectPrivateMode(
function(is_private) {
	var browser_type = typeof is_private === 'undefined' ? 'cannot detect' : is_private ? 'private' : 'not private';
	console.log(browser_type);
	if(browser_type != 'not private'){
		$("#wgh_opt_selection").css('display', 'none');
	}
}
);
