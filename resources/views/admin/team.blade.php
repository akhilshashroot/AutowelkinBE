				<!-- Team  modal -->
				<div class="modal fade show" id="viewteam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
									
										<div class="modal-header">
											<h5 class="modal-title" id="teammodel">
												Teams
											</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">
													×
												</span>
											</button>
										</div>
										<div class="modal-body " >
											<table class="table m-table m-table--head-bg-success">
												<thead>
													<tr>
														<th>
															#
														</th>
														<th>
															Team Name
														</th>
														<th>
															Mail ID of team manager
														</th>
														<th>
															Actions
														</th>
														
													</tr>
												</thead>
												<tbody id="teamDiv">
													
												</tbody>
												<tfoot>
													
												</tfoot>
											</table>	
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">
												Close
											</button>
											
										</div>
										
									</div>
								</div>
							</div>
			<!-- End Team  modal -->	
		<!--  Add Team  modal -->
			<div class="modal fade show" id="newteam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
				<div class="modal-dialog " role="document">
					<div class="modal-content">
					<form id="teamForm" >
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">
								Add new team
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">
									×
								</span>
							</button>
						</div>
						<div class="modal-body">
								
								<div class="form-group">
									<label for="recipient-name" class="form-control-label">
										Team name:
									</label>
									<input type="text" class="form-control" id="recipient-name" name="teamname">
								</div>
							
							
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn btn-secondary" data-dismiss="modal">
								Close
							</button>
							<button  id="teamForm" class="btn btn-primary">
								Add team
							</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!-- end::Team Add  modal -->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

			<script>
	$("#teamForm").validate({
		
		rules: {
			teamname: {
			required: true,
			},
		
		},
		
		submitHandler: function(form) {
		var name= $('#recipient-name').val();

			$.ajax({
			url: 'admin/teams',
			type:"POST",
			data: {
			"_token": "{{ csrf_token() }}",
			name: name,
			}  , 
				
				success:function(response){
					$.notify({
					title: '<strong>Success!</strong>',
					message:"Team Added"
				},{
					type: 'success',
					z_index: 10000,
				});
					document.getElementById("teamForm").reset(); 

			},

			error: function(response) {
				$.notify({
					title: '<strong>Error!</strong>',
					message:"Team couldn't be added!"
				},{
					type: 'danger',
					z_index: 10000,
				});
			}
				
			
			});
			
		}
		})
			
			function viewallteams(){ 
			$.ajax({
				dataType:'json',
				url:'admin/teams',
				success:function(data){
					table ='';
					for (var i = 0, len = data.length; i < len; i++) {
						table +='<tr>';
						table +='<td>'+(i+1)+'</td><td id="td_id_'+data[i].team_id+'"><span id="Ed_team_id_'+data[i].team_id+'">'+data[i].name+'</span><td id="td_mail_in_'+data[i].team_id+'"><span id="ml_id_'+data[i].team_id+'"><input id="ml_in_'+data[i].team_id+'" type="text" value="'+data[i].mail_ids+'" name="mail" disabled="disabled"/></span></td><td><button class="btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--pill" onclick="deletes_team('+data[i].team_id+')" style="float:right;">  <i class="la la-trash"></i></button><button class="btn btn-outline-accent btn-sm m-btn m-btn--icon m-btn--pill" id="atag_id_'+data[i].team_id+'" data="Edit" style="float:right;margin-right: 2px;" onclick="edit_team('+data[i].team_id+',this);return false;">Edit</button> </td>';
				
						table +='<tr>';
					}
					$('#teamDiv').html(table);
					$('#viewteam').modal('show');     
					
				}
			});
			
		}
		function deletes_team(team_id){
		var result = confirm("Are you sure you want to Delete this Team? If you delete ,this will affect further datas which are related to this department and employee details. ");
		if(result){
				event.preventDefault();
				$.ajax({
							url:'admin/teams/'+team_id,
							dataType:'json',
							data:{
							"_token": "{{ csrf_token() }}",
							team_id: team_id,
							}  , 
							type:'DELETE',
							success:function(response){ 
						          if(response.flag==1){		
									$.notify({
	                               		title: '<strong>Success!</strong>',
	                               		message:"Team Deleted"
	                               	},{
	                               		type: 'success',
	                               		z_index: 10000,
	                               	});
								}
								else{
									$.notify({
										title: '<strong>Error!</strong>',
										message:"Team couldn't be Deleted"
									},{
										type: 'danger',
										z_index: 10000,
									});
								}


							}
						});
		}
	}

		/*** Edit teams */
	function edit_team(team_id,obj){
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
				url:'admin/teams/'+team_id,
					data:{
							"_token": "{{ csrf_token() }}",
							team_id: team_id,
							text2:text2,
							mail_ids:mail_ids,
						}  , 
					type:'PUT',
					dataType:'json',
					success:function(response){ 
						if(response.flag==1){								
	                   $.notify({
	                   		title: '<strong>Success!</strong>',
	                   		message:"Team Updated"
	                   	},{
	                   		type: 'success',
	                   		z_index: 10000,
	                   	});
	                   	}
	                   
	                   else{
	                   	$.notify({
	                   		title: '<strong>Error!</strong>',
	                   		message:"Team couldn't be Updated!"
	                   	},{
	                   		type: 'danger',
	                   		z_index: 10000,
	                   	});
	                   }
						$('#Ed_team_id_'+team_id).html(text2);	

					}
				});
			
		}
	
	}
		</script>

