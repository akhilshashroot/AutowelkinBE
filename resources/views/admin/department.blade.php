<!-- dept  model -->
<div class="modal fade show" id="viewDept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" >
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								
									<div class="modal-header">
										<h5 class="modal-title" id="teammodel">
											Departments
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
														Department Name
													</th>
													
													
												</tr>
											</thead>
											<tbody id="deptDiv">
												
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
		<!-- end::dept  model -->
		<!-- Team  model -->
		<div class="modal fade show" id="newdept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
							<div class="modal-dialog modal-sm" role="document">
								<div class="modal-content">
									<form id="adddept"  method="post">
										@csrf
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">
											Add new Dept
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
												Dept name:
											</label>
											<input type="text" class="form-control" id="recipient-name" name="deptname">
										</div>
										
										
									</div>
									<div class="modal-footer">
										<button type="reset" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button type="submit" class="btn btn-primary">
											Add Dept
										</button>
									</div>
									</form>
								</div>
							</div>
						</div>
		<!-- end::Team  model -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
		<script>
 $("#adddept").validate({
      
      rules: {
        deptname: {
          required: true,
        },
     
      },
     
      submitHandler: function(form) {
     var name= $('#recipient-name').val();

        $.ajax({
          url: APP_URL+'/newdept',
          type:"POST",
          data: {
        "_token": "{{ csrf_token() }}",
        dep_name: name,
        }  , 
            
            success:function(response){
				console.log(response.status);
				if(response.status) {
					$.notify({
					title: '<strong>Success!</strong>',
					message:"Department Added"
				},{
					type: 'success',
					z_index: 10000,
				});
					document.getElementById("adddept").reset(); 
				} else {
					$.notify({
						title: '<strong>Error!</strong>',
						message:"Department not added"
					},{
						type: 'danger',
						z_index: 10000,
					});
				}
          },

          error: function(response) {
            $.notify({
									title: '<strong>Error!</strong>',
									message:"Department not added"
								},{
									type: 'danger',
									z_index: 10000,
								});
           }
            
          
        });
        
      }
    })
    </script>
