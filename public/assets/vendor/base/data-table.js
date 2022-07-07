//== Class definition
var datatable;
var DatatableRemoteAjaxDemo = function () {
	//== Private functions

	// basic demo
	var demo = function () {

		var datatable = $('#ajax_data').mDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {//url: 'http://keenthemes.com/metronic/preview/inc/api/datatables/demos/default.php'
						url: 'userdata'
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
			columns: [
//				{
//				field: "user_id",
//				title: "#",
//				//sortable: 'asc', // disable sort for this column
//				width: 40,
//				selector: false,
//				textAlign: 'center'
//			}, 
					  {
				field: "emp_id",
				title: "EMP ID",
				sortable: 'asc', // default sort
				//filterable: true, // disable or enable filtering
				width: 50
				// basic templating support for column rendering,
				//template: '{{emp_id}} '
			}, {
				field: "fullname",
				title: "Full Name",
				width: 170,
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
				field: "phone",
				title: "Phone",
				width: 100
			}, {
				field: "email",
				title: "Email",
				width: 200
			}, {
				field: "name", 
				title: "Team",
				//sortable: 'asc', 
				width: 250,
				template: function (row) {
		
				return '<span class="m-badge m-badge--primary m-badge--dot"></span> <span class="m--font-bold m--font-Online">' + row.name+ '</span>';
			}
				 
			}, {
				field: "PE",
				title: "Scores",
				width: 50,
				//sortable: 'asc',
				template: function (row) {
					// callback function support for column rendering
					//return row.fullname + ' - ' + row.user_id;
					return row.PE+' | '+row.CE;
				}
			}/*,  {
				field: "role",
				title: "Status",
				width: 50,
				// callback function support for column rendering
				template: function (row) {
					var status = {
						1: {'title': 'Pending', 'class': 'm-badge--brand'},
						2: {'title': 'Delivered', 'class': ' m-badge--metal'},
						3: {'title': 'Canceled', 'class': ' m-badge--primary'},
						4: {'title': 'Success', 'class': ' m-badge--success'},
						5: {'title': 'Info', 'class': ' m-badge--info'},
						6: {'title': 'Danger', 'class': ' m-badge--danger'},
						7: {'title': 'Warning', 'class': ' m-badge--warning'}
					};
					return '<span class="m-badge ' + status[row.role].class + ' m-badge--wide">' + status[row.role].title + '</span>';
				}
			}*/, {
				field: "Actions",
				width: 110,
				title: "Actions",
				sortable: false,
				overflow: 'visible',
				template: function (row) {
					var dropup = (row.getDatatable().getPageSize() - row.getIndex()) <= 4 ? 'dropup' : ''; 

					return '\<a href="./performance/'+row.user_id+'"   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Performance">\
							<i class="la la-tachometer"></i>\
						</a>\
						\<a href="javascript:;" onClick="edituser('+row.user_id+')" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\
							<i class="la la-edit"></i>\
						</a>\
						<a href="javascript:;" onClick="deleteuser('+row.user_id+')"  class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">\
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
			query.team_id = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load(); 
		}).val(typeof query.team_id !== 'undefined' ? query.team_id : '');

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

function edituser(id){

	$.ajax({
		url:"./view_data/"+id,
		type: "POST",
		dataType:'json',
		success: function(data){
			//alert(data.user_id);
			//datatable.load();
			$('#fullname').val(data.fullname);
			$('#user_id').val(data.user_id);
			$('#empid').val(data.emp_id);
			$('#email').val(data.email);
			$('#phone').val(data.phone);
			$('#dob').val(data.dob);
			$('#date_of_join').val(data.date_of_join);
			$("#dept").val(data.dep_id); 
			$("#team").val(data.team_id);
			$("#designation").val(data.desgn_id);
			$('input[name=gender][value='+data.gender+']').attr('checked', true); 
			$('#myModalLabel').html('Update Details');
			$("#updateModel").modal('show');
				}
			});
}
function addNew(){
	$('#user_id').val('');
	$('#fullname').val('');
	$('#empid').val('');
	$('#user_id').val();
	$('#email').val('');
	$('#phone').val('');
	$('#dob').val('');
	$('#date_of_join').val('');
	$("#dept").val('');
	$("#team").val('');	
	$("#designation").val('');
	$('#myModalLabel').html('Add new Employee');
	$("#updateModel").modal('show');
}
function deleteuser(id){
	if (confirm('Are you sure you want to delete this user?'))	{
		$.ajax({
				url:"./delete_emp/"+id,
				type: "POST",
				success: function(data){
					alert("deleted ");
					//datatable.load();
				}
			});
	}
}