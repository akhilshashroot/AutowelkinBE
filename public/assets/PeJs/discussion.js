var page = 0;
var pagination_start = 1;
var pagination_end = 10;
var totalPage;
var selectedPage = 1;

var Discussion_title = {
	count: 1,
	title_id: 0,
	create(){
		var title = $('.summernote').summernote('code');
		var url = Api_url.create_title;
		$.ajax({
            type: "POST",
            data: {title},
            url: url,
            dataType:'json',
            success: Discussion_title.create_success,
            error: Discussion_title.error
        });
	},

	create_success(result){
		if(result.status === true){
			$('.summernote').summernote('code', '');
			$('#summernote').summernote('code', '');
			$("#m_summernote_modal").modal('toggle');
			Discussion.get_all_list();
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

	error(err){
		console.log(err);
	},

	list(page){
		var limit = 10;
		var url = Api_url.get_all_title;
		var auther = $("#auther").val();
		var title = $("#search-title").val();
		$.ajax({
            type: "POST",
            data: {page, limit, auther, title},
            url: url,
            dataType:'json',
            success: Discussion_title.list_success,
            error: Discussion_title.error
        });
	},

	list_success(result){
		
		var count = Discussion_title.count
		if(result.status === true){

			var data = result.data;
			var total_row = result.total_row;
			
			$(data).each(function(){
				var fullname = '';
				if(this.fullname != null){
					fullname = this.fullname;
				}else{
					fullname = this.name;
				}
				var discussion_url = Api_url.base_url+'discussion/details/'+this.id;
				console.log(this.user_id);
				if((usertype == 'admin') || (user_id == this.user_id)){
					var delete_url = '<a href="javascript:void(0)" onclick="Discussion_title.title_remove('+this.id+')">remove</a>';
					var edit_url = '<a href="javascript:void(0)" onclick="Discussion_title.title_details('+this.id+')">edit</a>';
				}else{
					var delete_url = '';
				}

				$("#titles").append('\
						<tr>\
							<th scope="row">'+count+'</th>\
							<td><a href="'+discussion_url+'">'+this.title+'</a></td>\
							<td>'+fullname+'</td>\
							<td>'+this.date+'</td>\
							<td>'+delete_url+' '+edit_url+'</td>\
						</tr>\
					');

				count++;
			});


			if(total_row > 0){
				Discussion_title.pagination(total_row);
			}

		}
	},

	title_details(title_id){
		this.title_id = title_id;
		console.log(title_id);
		var url = Api_url.get_title_details;
		$.ajax({
            type: "GET",
            url: url+title_id,
            dataType:'json',
            success: Discussion_title.details_success,
            error: Discussion_title.error
        });
	},

	details_success(result){
		if(result.status == true){
			var data = result.data;
			var title = data.title;
			$("#m_summernote_modal").modal("toggle");
			$('.summernote').summernote('code', title);
			$("#title-btn").addClass("hide");
			$("#title-btn-update").removeClass("hide");
		}
		console.log(result);
	},

	title_remove(title_id){
		var confirmation = confirm('Are you sure you want to delete this title?');
		if(confirmation == true){
			var url = Api_url.remmove_title;
			$.ajax({
	            type: "POST",
	            data: {title_id},
	            url: url,
	            dataType:'json',
	            success: function(result){
	            	console.log(result);
	            	if(result.status == true){
	            		$("#titles").html('');
	            		Discussion_title.list(page);
	            	}else{
	            		alert(result.message);
	            	}
	            },
	            error: Discussion_title.error
	        });
		}
	},

	pagination(total_num_row) {

        var req_num_row = 10;
        var $tr = $('tbody tr');
        var num_pages = 0;
        if (total_num_row % req_num_row == 0) {
            num_pages = total_num_row / req_num_row;
        }
        if (total_num_row % req_num_row >= 1) {
            num_pages = total_num_row / req_num_row;
            num_pages++;
            num_pages = Math.floor(num_pages++);
        }
        $('#pagination').html('');
        for (var i = 1; i <= num_pages; i++) {
            if (i >= pagination_start && i <= pagination_end) {
                if (i == selectedPage) {
                    $('#pagination').append("<li class='active page-item'><a class='page-link'>" + i + "</a></li>");
                } else {
                    $('#pagination').append("<li class='page-item'><a class='page-link'>" + i + "</a></li>");
                }
            }

        }
        $tr.each(function (i) {
            $(this).hide();
            if (i + 1 <= req_num_row) {
                $tr.eq(i).show();
            }

        });

        $('#pagination a').click(function (e) {
            e.preventDefault();
            $tr.hide();
            page = $(this).text();
            var orig_page = page;
            Discussion_title.count = ((orig_page-1)*10)+1;
            /** for seral number printing */
            var slPage = page - 1;
            selectedOffset = (slPage * 10);

            if (orig_page == ' ... ') {
                return false;

            }
            selectedPage = orig_page;

            if(discussion_id == null){
            	$("#titles").html('');
            	Discussion_title.list(orig_page)
            }
            else{
            	$("#discussion-titles").html('');
            	console.log(orig_page);
            	Discussion.list(orig_page);
            }

            if (orig_page == 1) {
                return false;
            }

            if (orig_page == totalPage) {
                // return false;
                pagination_start = totalPage - 10;
                pagination_end = totalPage;
            }

            if (orig_page == pagination_end) {
                pagination_start = pagination_start + 8;
                pagination_end = pagination_end + 8;
            }

            if (orig_page == pagination_start) {
                pagination_start = pagination_start - 8;
                pagination_end = pagination_end - 8;
            }

        });
        
    },

    updaet_title(){
    	var title = $('.summernote').summernote('code');
    	var title_id = this.title_id;

    	console.log(title_id);

    	var url = Api_url.update_title;
		$.ajax({
            type: "POST",
            data: {title, title_id},
            url: url,
            dataType:'json',
            success: Discussion_title.create_success,
            error: Discussion_title.error
        });
    }
}



var Discussion = {

	create(){
		var title = $('#summernote').summernote('code');
		var url = Api_url.post_discusion;
		$.ajax({
            type: "POST",
            data: {title, discussion_id},
            url: url,
            dataType:'json',
            success: Discussion_title.create_success,
            error: Discussion_title.error
        });
	},

	list(page){
		
		var limit = 10;
		var url = Api_url.get_discussion_list;
		$.ajax({
            type: "POST",
            data: {page, limit, discussion_id},
            url: url,
            dataType:'json',
            success: Discussion.list_success,
            error: Discussion_title.error
        });
	},

	list_success(result){
		console.log(result);
		if(result.status === true){

			var data = result.data;
			var total_row = result.total_row;

			$(data).each(function(){
				var fullname = '';
				if(this.fullname != null){
					fullname = this.fullname;
				}else{
					fullname = this.name;
				}
				var discussion_url = Api_url.base_url+'discussion/details/'+this.id;
				if((usertype == 'admin') || (user_id == this.user_id)){
					var delete_url = '';
					var edit_url = '';
				}else{
					var delete_url = '';
					var edit_url = '';
				}
				
				$("#discussion-titles").append('\
							<div class="m-widget3__item">\
							<div class="m-widget3__header">\
								<div class="m-widget3__user-img">\
									<img class="m-widget3__img" src="'+base_url+'assets/img/user/'+this.emp_id+'.jpg" alt="">\
								</div>\
								<div class="m-widget3__info">\
									<span class="m-widget3__username">\
										'+fullname+'\
									</span>'+delete_url+edit_url+'<br>\
									<span class="m-widget3__time">\
										'+this.date+'\
									</span>\
								</div>\
							</div>\
							<div class="m-widget3__body">\
								<p class="m-widget3__text">\
									'+this.discussion+'\
								</p>\
							</div>\
						</div>\
					')
			});


			if(total_row > 0){
				Discussion_title.pagination(total_row);
			}

		}
	},


	get_all_list(){
		if(discussion_id == null){
			$("#titles").html('');
			Discussion_title.list(0)
		}else{
			$("#discussion-titles").html('');
			Discussion.list(0);
		}

		/*if(discussion_id == null){
			Discussion_title.create();
		}else if(discussion_subtitle_id == null){
			Discussion_Subtitle.create();
		}else{
			Discussion.create();
		}*/
	}
}



var Discussion_Subtitle = {

	create(){

		var title = $('#summernote_subtopic').summernote('code');
		var url = Api_url.create_subtitle;
		$.ajax({
            type: "POST",
            data: {title, discussion_id},
            url: url,
            dataType:'json',
            success: function(result){
            	if(result.status === true){
					$('#summernote_subtopic').summernote('code', '');
					$("#m_summernote_subtitle_modal").modal('toggle');
					$.notify({
						title: '<strong>Success!</strong>',
						message: result.message
					},{
						type: 'success'
					});
					location.reload();
				}else{
					$.notify({
						title: '<strong>Failed!</strong>',
						message: result.message
					},{
						type: 'danger'
					});
				}
            },
            error: Discussion_title.error
        });
	},



	list(page){
		var limit = 10;
		var url = Api_url.list_all_subtitle;
		var auther = $("#auther").val();
		var title = $("#search-title").val();
		$.ajax({
            type: "POST",
            data: {discussion_id, page, limit, auther, title},
            url: url,
            dataType:'json',
            success: Discussion_Subtitle.list_success,
            error: Discussion_title.error
        });
	},

	list_success(result){
		
		var count = Discussion_title.count
		if(result.status === true){

			var data = result.data;
			var total_row = result.total_row;
			
			$(data).each(function(){
				var fullname = '';
				if(this.fullname != null){
					fullname = this.fullname;
				}else{
					fullname = this.name;
				}
				var discussion_url = Api_url.base_url+'discussion/details/'+this.id+'/'+this.ds_id;
				console.log(this.user_id);
				if((usertype == 'admin') || (user_id == this.user_id)){
					var delete_url = '<a href="javascript:void(0)" onclick="Discussion_Subtitle.title_remove('+this.ds_id+')">remove</a>';
				}else{
					var delete_url = '';
				}

				$("#titles").append('\
						<tr>\
							<th scope="row">'+count+'</th>\
							<td><a href="'+discussion_url+'">'+this.sub_topic+'</a></td>\
							<td>'+fullname+'</td>\
							<td>'+this.date+'</td>\
							<td>'+delete_url+'</td>\
						</tr>\
					');

				count++;
			});


			if(total_row > 0){
				Discussion_title.pagination(total_row);
			}

		}
	},


	title_remove(ds_id){

		var confirmation = confirm('Are you sure you want to delete this title?');
		if(confirmation == true){
			var url = Api_url.remove_subtitle;
			$.ajax({
	            type: "POST",
	            data: {ds_id},
	            url: url,
	            dataType:'json',
	            success: function(result){
	            	console.log(result);
	            	if(result.status == true){
	            		$("#titles").html('');
	            		Discussion_Subtitle.list(page);
	            	}else{
	            		alert(result.message);
	            	}
	            },
	            error: Discussion_title.error
	        });
		}
	},


}


var Api_url = {
	base_url: base_url,
	create_title: this.base_url+'discussion/create_discussion_title',
	get_all_title: this.base_url+'discussion/list_all_title',
	post_discusion: this.base_url+'discussion/post_discusion',
	get_discussion_list: this.base_url+'discussion/get_discussion_list',
	remmove_title: this.base_url+'discussion/remmove_title',
	create_subtitle: this.base_url+'discussion/create_subtitle',
	list_all_subtitle: this.base_url+'discussion/list_all_subtitle',
	remove_subtitle: this.base_url+'discussion/remove_subtitle',
	get_title_details: this.base_url+'discussion/get_title_details/',
	update_title: this.base_url+'discussion/update_title',
}

$(document).ready( function(){
	Discussion.get_all_list();

});

$("#title-btn").on('click', function(){
	// console.log('code reached');
	if(discussion_id == null){
		Discussion_title.create();
	}else{
		Discussion.create();
	}
});

$("#title-btn-update").on('click', function(){
	if(discussion_id == null){
		Discussion_title.updaet_title();
	}
});

$("#subtitle-btn").on('click', function(){
	Discussion_Subtitle.create();
})

/*$(".summernote").summernote({
    onImageUpload: function(files, editor, welEditable) {
    	console.log('ccc');
        sendFile(files[0], editor, welEditable);
    }
})*/

$(".summernote").summernote({
	height:150,
    callbacks: {
        onImageUpload: function(files) {
            uploadImage(files[0]);
        }
    }
})

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
            $('#summernote').summernote("insertNode", image[0]);
        },
        error: function(data) {
            console.log(data);
        }
    });
}


$(document).on('change', '#auther', function(){
	$("#titles").html('');
	Discussion_title.list();
});

$(document).on('keyup', '#search-title', function(){
	$("#titles").html('');
	Discussion_title.list();
})