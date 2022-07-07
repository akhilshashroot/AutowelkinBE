//== Class definition
var datatable;
var DatatableRemoteAjaxDemo = function () {
	//== Private functions

	// basic demo
	var demo = function () {

		var datatable = $('#request_data').mDatatable({
//			//testing of order
//				order:[[ 1, "desc" ]],
//			//Close testing of order
			
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {//url: 'http://keenthemes.com/metronic/preview/inc/api/datatables/demos/default.php'
						url: 'userRequests'
					}
				},
				pageSize: 10,
				saveState: {
					cookie: true,
					webstorage: true
				},
				serverPaging: true,
				serverFiltering: true,
				serverSorting: true
			},

			// layout definition
			layout: {
				theme: 'default', // datatable theme
				class: '', // custom wrapper class
				scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
				footer: false // display/hide footer
			},

			// column sorting
			sortable: true,

			// column based filtering
			filterable: false,

			pagination: true,

			// columns definition
			columns: [{
				field: "lv_aply_date",
				title: "#",
				sortable: 'desc', // disable sort for this column
				width: 100,
				selector: false,
				textAlign: 'center',
				//test
				template: function (row) {
						
						apply_date = new Date(row.lv_aply_date * 1000);	// Convert the passed timestamp to milliseconds
						apply_y = apply_date.getFullYear();
						apply_m = ('0' + (apply_date.getMonth() + 1)).slice(-2);	// Months are zero based. Add leading 0.
						apply_d = ('0' + apply_date.getDate()).slice(-2);
						date_aplyd = apply_d + '-' + apply_m + '-' + apply_y;
						//close test
						return '<span class="m-badge m-badge--wide">' +date_aplyd+ '</span>';
//					return '<span class="m-badge ' + status[row.lv_type].class + ' m-badge--wide">' + status[row.lv_type].title + '</span>';
				}
				//Close test
				
			},  {
				field: "lv_type",
				title: "Type",
				width: 50,
				// callback function support for column rendering
				template: function (row) {
					var status = {
						1: {'title': 'CL', 'class': 'm-badge--brand'},
						2: {'title': 'ML', 'class':' m-badge--warning'},
						3: {'title': 'WFH', 'class': ' m-badge--success'},
						4: {'title': 'LOP', 'class': ' m-badge--danger'},
						5: {'title': 'SW', 'class': ' m-badge--info'}
					};
					return '<span class="m-badge ' + status[row.lv_type].class + ' m-badge--wide">' + status[row.lv_type].title + '</span>';
				}
			}, {
				field: "fullname",
				title: "Full Name",
				width: 125,
				//filterable: false,
				template: function (row) { 
					// callback function support for column rendering
					//return row.fullname + ' - ' + row.user_id;
					return '\<a target="_blank" href="./gouser/'+row.user_id+'"   class="m-portlet__nav-link btn m-btn">\
							 '+row.fullname +'\
							</a>\
					';
				}
			}, {
				field: "approvedby",
				title: "Consent of",
				width: 100
			}, 
					  
//		    {
//				field: "lv_no",
//				title: "No",
//				width: 50
//			},
					  
			{
				field: "lv_purpose", 
				title: "Message",
				//sortable: 'asc', 
				width: 300,
				template: function (row) {
				msg=row.lv_purpose.replace(/\n/g,"<br>");
				return '<span class="m--font-bold m--font-Online">' +msg+ '</span>';
			}

				 
			},
			//test date field
			{
				field: "lv_date", 
				title: "Dates Requested",
				//sortable: 'asc', 
				width: 100,
				template: function (row) {
//				msg=row.lv_purpose.replace(/\n/g,"<br>");
				//test
				if(row.lv_date!=0){
					fr_date = new Date(row.lv_date * 1000);	// Convert the passed timestamp to milliseconds
					frmyyyy = fr_date.getFullYear();
					frmmm = ('0' + (fr_date.getMonth() + 1)).slice(-2);	// Months are zero based. Add leading 0.
					frmdd = ('0' + fr_date.getDate()).slice(-2);
					frmdate = frmdd + '-' + frmmm + '-' + frmyyyy;
					if(row.lv_date_to!=0){
						
						to_date = new Date(row.lv_date_to * 1000);	// Convert the passed timestamp to milliseconds
						to_yyyy = to_date.getFullYear();
						to_mm = ('0' + (to_date.getMonth() + 1)).slice(-2);	// Months are zero based. Add leading 0.
						to_dd = ('0' + to_date.getDate()).slice(-2);
						date_to = to_dd + '-' + to_mm + '-' + to_yyyy;
						//close test
						return '<span class="m-badge m-badge--wide">' +frmdate+ '<br/> to <br/>'+date_to+'</span><br/><span class="m-badge m-badge--warning text-center" style="-webkit-padding-start: 10px;-webkit-padding-end: 10px;" >No.Days: <b>'+row.lv_no+' </b></span>';    
    
						
					}
					else{
						
						return '<span class="m-badge m-badge--wide">' +frmdate+ '</span><br/><span class="m-badge m-badge--warning text-center" style="-webkit-padding-start: 10px;-webkit-padding-end: 10px;">No.Days: <b>'+row.lv_no+' </b></span>';
						
					}
					
					
				}
					
			  } 
			}, 
			//Close date field test
					  
			 {
				field: "lv_img", 
				title: "Files",
				//sortable: 'asc', 
				width: 50,
				template: function (row) {
					if(row.lv_img){
						var dat='<a href="/assets/userfiles/'+row.lv_img+'" target="_blank"  class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="download">\
							<i class="fa fa-download"></i>\
						</a>\
						';
					}else{
						var dat=' ';
					}
				
				return dat;
			}

				 
			}, {
				field: "lv_status",
				title: "Status",
				width: 100,
				//sortable: 'asc',
					template: function (row) {
					var status = {
						0: {'title': 'Pending', 'class': 'm-badge--primary'},
						2: {'title': 'Rejected by', 'class':' m-badge--danger'},
						1: {'title': 'Approved by', 'class': ' m-badge--success'}						
					};
					return '<span id="'+row.lv_id+'" class="m-badge ' + status[row.lv_status].class + ' m-badge--wide">' + status[row.lv_status].title + ' '+row.appr_person+'</span>';
				}
			}, {
				field: "Actions",
				width: 110,
				title: "Actions",
				sortable: false,
				overflow: 'visible',
				template: function (row) {
					var dropup = (row.getDatatable().getPageSize() - row.getIndex()) <= 4 ? 'dropup' : ''; 

					return '\<a  href="javascript:;" onClick="requestapprove('+row.lv_id+')"  class="m-portlet__nav-link btn m-btn btn-success m-btn--icon m-btn--icon-only m-btn--pill" title="Approve">\
							<i class="fa fa-check-square-o"></i>\
						</a>\
						\<a href="javascript:;" onClick="requestreject('+row.lv_id+')" class="m-portlet__nav-link btn m-btn btn-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Reject">\
							<i class="fa fa-ban"></i>\
						</a>\
						<a href="javascript:;" onClick="deleterequest('+row.lv_id+')"  class="m-portlet__nav-link btn m-btn btn-metal  m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">\
							<i class="la la-trash"></i>\
						</a>\
					';
				}
			}]
		});

		var query = datatable.getDataSourceQuery();

		$('#m_form_search').on('keyup', function (e) {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.generalSearch = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		}).val(query.generalSearch);

		$('#m_form_name').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.lv_status = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load(); 
		}).val(typeof query.lv_status !== 'undefined' ? query.lv_status : '');

		$('#m_form_type').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.Type = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		}).val(typeof query.Type !== 'undefined' ? query.Type : '');


		$('#m_form_name, #m_form_type').selectpicker();

	

	};

	return {
		// public functions
		init: function () {
			demo();
		}
			
	};

}();

jQuery(document).ready(function () {
	DatatableRemoteAjaxDemo.init();
	

});
		function requestapprove(id){ 	
			var result = confirm("Are you sure you want to approve this request?");
			if (result){
			var approve_per = prompt("Please enter your name","");
			if(approve_per != ""){
//				alert(approve_per);
				$.ajax({
					url:'../TestMail/requestapprove',
					type:'POST', 
					data:{id,approve_per},
					success:function(data){			
						if(data==1){								
							$.notify({
								title: '<strong>Success!</strong>',
								message:"Updated!"
							},{
								type: 'success',
								z_index: 10000,
							});
							location.reload();
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
			
		}
		else{
			alert("Please enter your name ");
		}
				}//end confirm
	}

		function requestreject(id){ 	
			
			//Start Confirmation
	var result = confirm("Are you sure you want to reject this request?");
			if(result){
				
			var rejected_per = prompt("Please enter your name","");
			if(rejected_per != ""){
//				alert(rejected_per);
				 $.ajax({
						url:'../TestMail/requestreject',
						type:'POST', 
						data:{id,rejected_per},
						success:function(data){			
							if(data==1){								
								$.notify({
									title: '<strong>Success!</strong>',
									message:"Updated!"
								},{
									type: 'success',
									z_index: 10000,
								});
								location.reload();
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
				
			}
				
			else{
				alert('Please enter your name');
			}
			}
		}
			function deleterequest(id){ 	
	
			$.ajax({
			url:'deleterequest',
			type:'POST', 
			data:{id},
			success:function(data){			
				if(data==1){								
		$.notify({
				title: '<strong>Success!</strong>',
				message:"Deleted!"
			},{
				type: 'success',
				z_index: 10000,
			});
			location.reload();
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
		}