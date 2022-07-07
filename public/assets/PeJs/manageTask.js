$('#add_task').ajaxForm({
	dataType:'json', 
	success: function(response, status, xhr, $form) {
		if(response.status==0){	
			$('#add_task').clearForm();	
			var task_attachment = "";
			if(response.tasks_attached_files.length > 0){
				response.tasks_attached_files.forEach(attachment => {
					task_attachment += " <a target='_blank' href='"+base_url+"assets/tasks_attachments/"+attachment+"' ><i class='fa fa-save text-success'></i></a>";
				})
			}

            $("#added_list").prepend(`
			<tr class='task_`+response.record_id+`'>
				<th scope='row'><a href=javascript:; onclick="viewTaskDetails(`+response.record_id+`)">`+response.data.title+`</a></th>
				<td>`+response.assignee+`</td>
				<td><i class="fa fa-circle text-danger"></i></td>
				<td>--</td>
				<td>`+task_attachment+`</td>
				<td><a href=javascript:; onclick="removeTask(`+response.record_id+`)" style="margin-right: 15px;"><i class='fa fa-trash'></i></a></td>
			</tr>
		   			 `);						
	$.notify({
			title: '<strong>Success!</strong>',
			message:"Task has been assigned successfully"
		},{
			type: 'success',
			z_index: 10000,
        });
        
		}else{
		$.notify({
			title: '<strong>Error!</strong>',
			message:"Failed to save! please try again!"
		},{
			type: 'danger',
			z_index: 10000,
		});
	}
	}
}); 

function selectPeriod(period){
	var select = `<label for="recipient-name" class="form-control-label">
                            Select Deadline:
                        </label>`;
	switch (period) {
		case "ONE": select  += `<input type='date' name='date' class='form-control m-input' value='' placeholder='dd/mm/yyyy' />`;
			break;
		case "WEEK" : select  += `<select name='date' class='form-control '>
		<option value="0">Sunday</option>
		<option value="1">Monday</option>
		<option value="2">Tuesday</option>
		<option value="3">Wednesday</option>
		<option value="4">Thursday</option>
		<option value="5">Friday</option>
		<option value="6">Saturday</option>
	  </select> `;
			break;
			
		default: select  += " <span class=form-control >Disabled </span>";
			break;
	}
	$("#datePick").html(select);
}

function removeTask(asgnmnt_id) {

    if (confirm("Are you sure you want to delete the task?") == true) {
        $.ajax({
            dataType: 'json',
            type: "POST",
            url:'/Admin/deleteTask',
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

function editdeadline(asgnmnt_id) {

    if (confirm("Are you sure you want to update the deadline?") == true) {
        $.ajax({
            dataType: 'json',
            type: "POST",
            url:'/Admin/editdeadline',
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


function viewTaskDetails(asgnmnt_id){
	$.ajax({
		dataType:'json',
		type:"POST",
		url:'/Admin/viewTaskDetails',
		data:{asgnmnt_id},
		success:function(data){
			$("#task_title").html(data.title);
			$("#tasktable_title").html(data.title); 
            $("#assigned_to").html(data.assignee);
            $("#assigner").html(data.assigner);
			$("#assigned_date").html(data.realDate);
			$("#task_details").html(data.body);
			$("#task_status").html(data.status);
			$("#task_deadline").html(data.date + "<a href='javascript:;' style='margin-left:10px' onclick='datePick("+data.asgnmnt_id+");'><i class='fa fa-edit'></i></a>");
			$("#task_id").val(data.asgnmnt_id);
			if(data.status=="Done"){
				$("#task_checkbox").attr('checked',true);
			}else{
				$("#task_checkbox").attr('checked',false);
			}
			var task_attachment = "";
			if(data.task_attachment != ""){
				data.task_attachment.forEach(attachment => {
					task_attachment += " <a target='_blank' href='"+base_url+"assets/tasks_attachments/"+attachment+"' ><i class='fa fa-save text-success'></i></a>";
				});	
			}
			$("#task_attachments").html(task_attachment);

			var deComments = "";
			if(data.comments.length>=1){
			data.comments.forEach(element => {
				conversation=element.comments.replace(/\n/g,"<br/>");
				 deComments += `<p><b>`+element.name+`</b><span class='m--font-danger' style='float:right'>`+element.date+`</span><br>`+conversation+ `</p><hr>`
			});
		}else{
			deComments += "no comments!";
	   }

	  
			$("#task_comments").html(deComments);
			$("#admintasks").modal("show");

		}
	});

}

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
		$('#task_comments').append(`<p><br><b>`+response.comment.name+`</b><span class='m--font-danger' style='float:right'>`+response.comment.date+`</span><br> `+response.comment.comments+`  </p><hr>`);
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


function datePick(s){
	document.getElementById("task_deadline").innerHTML = "<input type='date' name='date' class='form-control m-input' value='' id='mdatepick' placeholder='dd/mm/yyyy' onchange='updateDate(this.value,"+s+")'></input>";
}

function updateDate(date,taskid){

	$.ajax({
		dataType:'json',
		type:"POST",
		url:'/Admin/updateTaskdate',
		data:{date,taskid},
		success:function(data){

			if(data.status){
				$.notify({
					title: '<strong>Success!</strong>',
					message:data.message
				},{
					type: 'success',
					z_index: 10000,
				});
				$("#task_deadline").html(data.date + "<a href='javascript:;' style='margin-left:10px' onclick='datePick("+data.taskid+");'><i class='fa fa-edit'></i></a>");
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