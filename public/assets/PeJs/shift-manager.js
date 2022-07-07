var Shift_manager = {

	/**
	 * used to store shift created persons detils.
	 * @type {Array}
	 */
	createdBy_a: [],

	/**
	 * Date picker id array
	 * @type {Array}
	 */
	datePick_a: [],

	/**
	 * Permission manager
	 */
	havePermission: [],

	/**
	 * managing comment edit option buttons
	 * @type {String}
	 */
	commentHide: 'show',

	/**
	 * recieving shifts
	 */
	loadShifts(){
		var url = ApiUrls.loadShifts;
		 $.ajax({
			type:'GET',
			url: url,
			dataType:'json',
			success: Shift_manager.loadShiftSuccess,
			error: Shift_manager.apiError
   		});
	},

	/**
	 * Preview Shift
	 * @return {[type]} [description]
	 */
	previewShift(){

		$("#preview-next-shift-btn").addClass('hide');
		$("#create-new-shift-btn").removeClass('hide');
		
		var url = ApiUrls.previewShift;
		 $.ajax({
			type:'GET',
			url: url,
			dataType:'json',
			success: Shift_manager.loadShiftSuccess,
			error: Shift_manager.apiError
   		});
	},

	/**
	 * recieving team members
	 */
	getTeamMembers(){
		var url = ApiUrls.getTeamMembers;
		 $.ajax({
			type:'GET',
			url: url,
			dataType:'json',
			success: Shift_manager.successTeamMembers,
			error: Shift_manager.apiError
   		});
	},

	/**
	 * success team members api call
	 */
	successTeamMembers(result){
		console.log(result);
		if(result.status == 'success'){
			var data = result.data;
			$(data).each(function (){
				console.log(this.fullname);
				$('.new-shift-members').append('\
					<option value="'+this.fullname+'">'+this.fullname+'</option>\
					')
			})
		}
	},

	/**
	 * shift success from api call
	 */
	loadShiftSuccess(result){
		// console.clear();
		console.log(result);
		if(result.status == 'success'){
			$("#shift-management-table").removeClass('hide');
			$("#new-shift-form").html('');
			var data = result.data;
			
			$(data).each(function(){
				$("#new-shift-form").append('\
						<tr>\
							<th style="vertical-align: inherit !important"><span class="m-badge m-badge--colo m-badge--wide m-badge--rounded '+this.day+'">'+this.day+'</span></th>\
							'+Shift_manager.printShifts(this.shift)+'\
							'+Shift_manager.printComments(this.comment, this.id)+'\
						</tr>\
						');
			});
			Shift_manager.getTeamMembers();
			$('.select2').select2();
			Shift_manager.enableDatePicker();
		}else{
			Shift_manager.setNewShiftHtml();
		}
	},

	/**
	 * comments printing and edit option to comment 
	 * @param  {[type]} comment [description]
	 * @param  {[type]} id      [description]
	 * @return {[type]}         [description]
	 */
	printComments(comment, id){
		var comments = '-';
		if(comment != ''){
			comments = comment;
		}

		
		commentHtml = '\
			<td>\
				<table>\
					<tr>\
						<td id="comments-'+id+'">\
							'+ comments +'\
						</td>\
					</tr>\
					<tr>\
						<td class="'+Shift_manager.commentHide+'">\
							<a href="javascript:void(0)" class="m-badge m-badge--brand m-badge--wide" data-toggle="modal" data-target="#comment_'+id+'">Edit</a>\
						</td>\
					</tr>\
					'+Shift_manager.commentEditHtml(id, comment)+'\
				</table>\
			</td>';
		

		return commentHtml;
	},

	/**
	 * comment edit modal html
	 * @param  {[type]} id [description]
	 * @return {[type]}    [description]
	 */
	commentEditHtml(id, comment){
		return '\
			<div class="modal fade" id="comment_'+id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
				<div class="modal-dialog modal-md" role="document">\
					<div class="modal-content">\
						<div class="modal-header">\
							<h5 class="modal-title" id="exampleModalLabel">\
								Edit Comment\
							</h5>\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								<span aria-hidden="true">\
									&times;\
								</span>\
							</button>\
						</div>\
						<div class="modal-body">\
							<form>\
								<div class="form-group">\
									<label for="recipient-name" class="form-control-label">\
										Comment:\
									</label>\
									<textarea id="new-comment-'+id+'" class="form-control" rows="5">'+comment+'</textarea>\
								</div>\
							</form>\
						</div>\
						<div class="modal-footer">\
							<button type="button" onclick="'+ Shift_manager.getAttributes("updateComment", "id") +'" id="'+id+'" class="btn btn-primary">\
								Save\
							</button>\
						</div>\
					</div>\
				</div>\
			</div>';
	},

	/**
	 * update shfit comment api call
	 * @param  {[type]} id [description]
	 * @return {[type]}    [description]
	 */
	updateComment(id){
		var url = ApiUrls.updateComment;
		var newComment = $("#new-comment-"+id).val();

		$.ajax({
			type:'POST',
			url: url,
			data:{
				commentId: id,
				comment: newComment,
			},
			dataType:'json',
			success: Shift_manager.commentUpdateSuccess,
			error: Shift_manager.apiError
   		});
	},

	/**
	 * update shift comment api call success
	 * @param  {[type]} result [description]
	 * @return {[type]}        [description]
	 */
	commentUpdateSuccess(result){
		console.log(result);
		if(result.status == 'success'){

			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'success'
			});

			var id = result.commentId;
			var comment = result.comment;
			$('#comments-'+id).html(comment);
			$('#comment_'+id).modal('toggle');
		}else{
			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'danger'
			});
		}
	},

	/**
	 * Initializing datepicker for all swap option modal
	 */
	enableDatePicker(){
		$(Shift_manager.datePick_a).each(function (){
			$("#swap-date-"+this).datepicker({
				"startDate": new Date(),
	            "minDate": new Date(),
	            "format": "yyyy-mm-dd",
	            "inline": true,
	            "autoclose": true,
	            "todayHighlight": true,
			});
		});
	},
	
	/**
	 * shifts printing into the html
	 */
	printShifts(shifts){
		var shiftHtml = "";
		$(shifts).each(function(){
			Shift_manager.printCreatedPersons(this.created_by);
			if(this.own_shift == 1){
				Shift_manager.havePermission.push(this.id);
				Shift_manager.commentHide = 'show';
			}else{
				Shift_manager.commentHide = 'hide';
			}

			shiftHtml = shiftHtml + '\
			<td>\
				<table id="shift-records-'+this.id+'">\
					<tr>\
						<td>\
							<span id="shift-users-'+this.id+'" class="shift-'+this.shift+'">'+this.users+'</span>\
						</td>\
					</tr>\
					<tr>\
						'+Shift_manager.printEditOptions(this.own_shift, this.id)+'\
					</tr>\
					<tr id="swaped-users-'+this.id+'"> '+Shift_manager.checkSwap(this.swap_user, this.swap, this.id)+'  </tr>\
					'+Shift_manager.swapOption(this.swap_user, this.id)+'\
					'+Shift_manager.editOption(this.id)+'\
				</table>\
			</td>';
			
		});
		return shiftHtml;
	},

	/**
	 * check swap status and return and html for swap notification	 
	 */
	checkSwap(swap_user, swap_a, shiftId){
		var swapHtml = '';
		var deletebtn = '';
		var havePermission = false;
		if(this.havePermission.includes(shiftId) == true){
			havePermission = true;
		}
		if(swap_user != 0){
			$(swap_a).each(function (){

				if (havePermission === true) {
					deletebtn = '<button id="'+this.id+'" onclick="Shift_manager.deleteSwap('+this.id+', '+shiftId+')" class="btn btn-sm-custom btn-danger m-btn--pill">X</button>';
				}
				
				swapHtml = swapHtml + '<tr id="swapped-det-'+this.id+'"><td>' + this.swap_user+ ' to '+ this.swap_date +' '+ deletebtn +' </td> </tr>';

			});
		}

		return swapHtml;
	},

	/**
	 * print created by person at top
	 */

	printCreatedPersons(createdBy){
		if(this.createdBy_a.includes(createdBy) == false){
			this.createdBy_a.push(createdBy);	
		}
		
		var createdBy_str = this.createdBy_a.join(", ");
		$("#shift-created-by").html(createdBy_str);
	},

	/**
	 * edit option html 
	 */
	printEditOptions(own_shift, id){
		if(own_shift == 1){
			return '<td>\
						<a href="javascript:void(0)" class="m-badge m-badge--brand m-badge--wide"  data-toggle="modal" data-target="#edit_modal_'+id+'">Edit</a>\
						<a  href="javascript:void(0)" class="m-badge m-badge--success m-badge--wide" data-toggle="modal" data-target="#swap_modal_'+id+'">Swap</a>\
					</td>';
		}else{
			return '';
		}
	},

	/**
	 * return edit option html
	 */
	editOption(id){
		return '\
			<div class="modal fade" id="edit_modal_'+id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
				<div class="modal-dialog modal-md" role="document">\
					<div class="modal-content">\
						<div class="modal-header">\
							<h5 class="modal-title" id="exampleModalLabel">\
								Edit Shift\
							</h5>\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								<span aria-hidden="true">\
									&times;\
								</span>\
							</button>\
						</div>\
						<div class="modal-body">\
							<div id="swaped-users-'+id+'">\
							</div>\
							<br/>\
							<form>\
								<div class="form-group">\
									<label for="recipient-name" class="form-control-label">\
										Members:\
									</label>\
									<select multiple id="edit-user-'+id+'" class="select2 form-control new-shift-members" style="width: 100%">\
									</select>\
								</div>\
							</form>\
						</div>\
						<div class="modal-footer">\
							<button type="button" onclick="'+ Shift_manager.getAttributes("editShifts", "id") +'" id="'+id+'" class="btn btn-primary">\
								Save\
							</button>\
						</div>\
					</div>\
				</div>\
			</div>';
	},

	/**
	 * return swap option html
	 */
	swapOption(swap_user, id){
			this.datePick_a.push(id);
			return '\
			<div class="modal fade" id="swap_modal_'+id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
				<div class="modal-dialog modal-md" role="document">\
					<div class="modal-content">\
						<div class="modal-header">\
							<h5 class="modal-title" id="exampleModalLabel">\
								Swap Shift\
							</h5>\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								<span aria-hidden="true">\
									&times;\
								</span>\
							</button>\
						</div>\
						<div class="modal-body">\
							<form>\
								<div class="col-md-6 pull-left">\
									<div class="form-group">\
										<label for="recipient-name" class="form-control-label">\
											Shift:\
										</label>\
										<select id="shif-'+id+'" class="form-control new-shift-members" style="width: 130px">\
										</select>\
									</div>\
								</div>\
								\
								<div class="col-md-6 pull-right">\
									<div>\
										<label for="message-text" class="form-control-label">\
											Swap with:\
										</label>\
										<select id="swap-'+id+'" class="form-control new-shift-members" style="width: 130px">\
										</select>\
									</div>\
								</div>\
								<div class="col-md-12 pull-right">\
									<div>\
										<label for="message-text" class="form-control-label">\
											Swap date:\
										</label>\
										<input type="text" class="form-control" placeholder="Start Date" id="swap-date-'+id+'">\
									</div>\
								</div>\
							</form>\
						</div>\
						<div class="modal-footer">\
							<button type="button" onclick="'+ Shift_manager.getAttributes("swapEmployees", "id") +'" id="'+id+'" class="btn btn-primary">\
								Swap\
							</button>\
						</div>\
					</div>\
				</div>\
			</div>';
	},

	/**
	 * shifts editting
	 */
	editShifts(id){
		var url = ApiUrls.editShifts;
		var users = $("#edit-user-"+id).val();
		var shiftId = id;

		$.ajax({
			type:'POST',
			url: url,
			data:{
				users: users,
				shiftId: shiftId,
			},
			dataType:'json',
			success: Shift_manager.shiftEditSuccess,
			error: Shift_manager.apiError
   		});

	},

	shiftEditSuccess(result){
		console.log(result);
		if(result.status == 'success'){
			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'success'
			});
			var shiftId = result.shiftId;
			var users = result.users;
			$('#shift-users-'+shiftId).html(users);
			$("#swaped-users-"+shiftId).html('');
			$('#edit_modal_'+shiftId).modal('toggle');
		}else{
			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'danger'
			});
		}
	},

	swapEmployees(id){
		var shift = $("#shif-"+id).val();
		var swap = $("#swap-"+id).val();
		var swapDate = $("#swap-date-"+id).val();
		var shiftId = id;
		var url = ApiUrls.swapShift;

		$.ajax({
			type:'POST',
			url: url,
			data:{
				shift: shift,
				swap: swap,
				shiftId: shiftId,
				swapDate: swapDate,
			},
			dataType:'json',
			success: Shift_manager.swapSuccess,
			error: Shift_manager.apiError
   		});
	},
	
	/**
	 * swap api call success function
	 */
	swapSuccess(result){
		// swaped-users-
		console.log(result);
		var havePermission = false;
		if(Shift_manager.havePermission.includes(id) == true){
			havePermission = true;
		}
		var deletebtn = '';
		if(result.status == 'success'){

			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'success'
			});

			var id = result.shift_id;
			var swaped = result.swaped;
			var swapDate = result.swapDate;
			
			if (havePermission === true) {
				deletebtn = '<button id="'+result.swap_id+'" onclick="Shift_manager.deleteSwap('+result.swap_id+', '+result.shift_id+')" class="btn btn-sm-custom btn-danger m-btn--pill">X</button>';
			}
			
			$("#shift-records-"+id).append('<tr id="swapped-det-'+result.swap_id+'"><td>'+ swaped+' to '+swapDate+' '+deletebtn+'</td> </tr>');
			$('#swap_modal_'+id).modal('toggle');
		}else{
			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'danger'
			});
		}
	},

	apiError(error){
		console.log(error);
	},

	/**
	 * creating new shift records
	 */
	newShift(id){
		/**
		 * read array values from the frontend
		 */
		var morning_shift = $("#"+id+"-morning").val();
		var evening_shift = $("#"+id+"-evening").val();
		var night_shift = $("#"+id+"-night").val();
		var off = $("#"+id+"-off").val();
		var comment = $("#"+id+"-comment").val();

		var url = ApiUrls.createShift;
		$.ajax({
			type:'POST',
			url: url,
			data:{
				morning_shift: morning_shift,
				evening_shift: evening_shift,
				night_shift: night_shift,
				off: off,
				day: id,
				comment: comment,
			},
			dataType:'json',
			success: Shift_manager.newShiftSuccess,
			error: Shift_manager.apiError
   		});
		
	},


	newShiftSuccess(result){
		if(result.status == 'success'){
			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'success'
			});
		}else{
			$.notify({
				title: '<strong>Failed!</strong>',
				message: result.message
			},{
				type: 'danger'
			});
		}
	},

	/**
	 * Print a new html for create a new shift
	 */
	setNewShiftHtml(){
		$("#action-shift-btn-group").removeClass('hide');
		$("#preview-next-shift-btn").removeClass('hide');
		$("#create-new-shift-btn").addClass('hide');
		$("#new-shift-form").html('');

		var regDays = [];

		var url = ApiUrls.getDays;

		$.ajax({
			type:'GET',
			url: url,
			async: false,
			dataType:'json',
			success: function(result){
				if(result.status == 'success'){
					regDays = result.data;
				}
			}
   		});

		var weekdays = [
				'Monday', 
				'Tuesday', 
				'Wednesday', 
				'Thursday', 
				'Friday', 
				'Saturday',
				'Sunday', 
			];

		var shift = [
				'morning',
				'evening',
				'night',
				'off'
			];

		var array_length = weekdays.length;
		for(var i=0; i<array_length; i++){

			if(regDays.includes(weekdays[i]) == true){
				continue;
			}
			
			$("#new-shift-form").append('\
				<tr>\
					<th>'+weekdays[i]+'</th>\
					<td>\
						<select class="select2 new-shift-members" multiple="multiple" name="'+weekdays[i]+'-'+shift[0]+'" id="'+weekdays[i]+'-'+shift[0]+'" style="width: 130px">\
						</select>\
					</td>\
					<td>\
						<select class="select2 new-shift-members" multiple="multiple" name="'+weekdays[i]+'-'+shift[1]+'" id="'+weekdays[i]+'-'+shift[1]+'" style="width: 130px">\
						</select>\
					</td>\
					<td>\
						<select class="select2 new-shift-members" multiple="multiple" name="'+weekdays[i]+'-'+shift[2]+'" id="'+weekdays[i]+'-'+shift[2]+'" style="width: 130px">\
						</select>\
					</td>\
					<td>\
						<select class="select2 new-shift-members" multiple="multiple" name="'+weekdays[i]+'-'+shift[3]+'" id="'+weekdays[i]+'-'+shift[3]+'" style="width: 130px">\
						</select>\
					</td>\
					<td>\
						<input type="text" class="form-control" style="width: 130px" id="'+weekdays[i]+'-comment" name="'+weekdays[i]+'-comment" />\
					</td>\
					<td>\
						<input type="button" onclick="'+ Shift_manager.getAttributes("newShift", "id") +'" class="btn btn-sm" value="Save" id="'+weekdays[i]+'" name="'+weekdays[i]+'" />\
					</td>\
				</tr>\
			');
			$('.select2').select2();
		}
		Shift_manager.getTeamMembers();
	},

	getAttributes (fn, att){
		var attrs = "Shift_manager."+fn+"(this.getAttribute('"+att+"'))";
		return attrs;
	},

	deleteSwap(swapId, shiftId){
		var confimation = confirm("Are you sure?");
		if(confimation == true){
			var url = ApiUrls.deleteSwap;
			$.ajax({
				type:'POST',
				url: url,
				data:{
					swapId: swapId,
					shiftId: shiftId,
				},
				dataType:'json',
				success: Shift_manager.swapDeleteSuccess,
				error: Shift_manager.apiError
	   		});
		}
	},

	swapDeleteSuccess(result){
		console.log(result);
		if(result.status == 'success'){
			$.notify({
				title: '<strong>Success!</strong>',
				message: result.message
			},{
				type: 'danger'
			});
			var swapId = result.swapId;
			var shiftId = result.shiftId;
			$("#swapped-det-"+swapId).html('');
		}else{
			$.notify({
				title: '<strong>Failed!</strong>',
				message: result.message
			},{
				type: 'danger'
			});
		}
	},

	getWeeks(){
		var url = ApiUrls.getWeeks;
		$.ajax({
			type:'GET',
			url: url,
			dataType:'json',
			success: Shift_manager.weekSuccess,
			error: Shift_manager.apiError
   		});
	},

	weekSuccess(result){
		console.log(result);
		if(result.status == 'success'){
			var data = result.data;
			$("#week-list").html('');
			$("#week-list").html('<option value="0">Please select</option>');
			$(data).each(function (){
				$("#week-list").append('\
					<option value="'+this.id+'">'+this.date+'</option>\
				')	
			});
		}
	},

	loadPreviousShifts(){
		var weekId = $("#week-list option:selected").val();
		$('#previous-shift-moal').modal('toggle');
		var url = ApiUrls.loadPreviousShift;
		$.ajax({
			type:'POST',
			url: url,
			data:{
				weekId: weekId
			},
			dataType:'json',
			success: Shift_manager.loadShiftSuccess,
			error: Shift_manager.apiError
   		});
	},

	/**
	 * recieve weekId for generate old shift and teamId passing from frotend. For admin use
	 * @return {[type]} [description]
	 */
	getWeekWithTeamId(){
		
		var teamId = $(this).val();
		var url = ApiUrls.getWeeks;
		$.ajax({
			type:'POST',
			url: url,
			data:{
				team_id: teamId
			},
			dataType:'json',
			success: Shift_manager.weekSuccess,
			error: Shift_manager.apiError
   		});
	},

	/**
	 * Shift record start for user, onloading function
	 * @return {[type]} [description]
	 */
	loadShiftMangerForUser(){
		Shift_manager.loadShifts();
		Shift_manager.getWeeks();
	}

}

/**
 * API urls of shift manager
 * @type {Object}
 */
var ApiUrls = {
	base_url : base_url,
	loadShifts: this.base_url + "Shift_manager/loadShifts", // shifts url
	getTeamMembers: this.base_url + "Shift_manager/getTeamMembers", // team members url
	createShift: this.base_url + "Shift_manager/createShift", // new shift creating url
	swapShift: this.base_url + "Shift_manager/swapShift", // shift swapping url
	editShifts: this.base_url + "Shift_manager/editShifts", // shift editing url
	deleteSwap: this.base_url + "Shift_manager/deleteSwap", // Swap Deleting
	getDays: this.base_url + "Shift_manager/getDays", // Already registerd week days
	previewShift: this.base_url + "Shift_manager/previewShift", // show saved next week shift
	updateComment: this.base_url + "Shift_manager/updateComment", // Update comment
	getWeeks: this.base_url + "Shift_manager/getWeeks", // Shift weeks
	loadPreviousShift: this.base_url + "Shift_manager/loadPreviousShift", // Load previous shifts
}

$(document).on('click', "#generate-old-shift-btn", Shift_manager.loadPreviousShifts);
$("#emp_team").on('change', Shift_manager.getWeekWithTeamId); // from admin panel
/*$(document).ready(Shift_manager.loadShifts());
$(document).ready(Shift_manager.getWeeks());*/

/*$(document).on('mouseleave', '.select2', function () {

	var id = $(this).siblings(".select2").val();
	console.log(id);
})*/