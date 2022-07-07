var chatRoom_aWidget = [];
var scrollFlagWidget = false;
var globalPageWidget = 1;
var chatType;
var selectedChat;
var listElmWidget = document.querySelector('#message-list');
var pageWidget = globalPageWidget;

/**
 * call function for older messages
 */

listElmWidget.addEventListener('scroll', function() {
	var divHight = $('#message-list')[0].scrollTop;

	if (divHight  == 0 && (globalPageWidget !=0 && scrollFlagWidget == true)) {
		pageWidget = globalPageWidget+9;

		Chat.getMessages(selectedChat, pageWidget);
	}
});

var Chat = {

	selectedPerson:'',  //save value of currently opened chat user_id and room_id

	/**
	 * call ajax for users list
	 */
	getAllUsers(){
		username = $("#search-user-widget").val();
		console.log(username);
		var url = apiUrls.listAllUsers;

        $.ajax({
			type:'POST',
			url: url,
			data:{username: username},
			dataType:'json',
			success: Chat.listUsers,
			error: Chat.apiError
   		});
	},

	/**
	 * success of getAllUsers function contain users list,
	 * and print users to the html
	 */
	listUsers(result){
		
		console.log(result);
		if(result.status === 'success'){
			var data = result.data;
			var dataLength = data.length;
			if(dataLength > 0 ){
				$("#users-list").html('');
				var chatType = "'from_user'";
				$(data).each(function () {
					
					var online_stat = '<span id="'+this.user_id+'" class="m-badge m-badge--secondary"></span>';
					if(this.online_status == 'online'){
						online_stat = '<span id="'+this.user_id+'" class="m-badge m-badge--success"></span>';
					}
					var fullName = '`'+this.fullname+'`';
					$("#users-list").append('\
							<div class="m-list-timeline__item">\
								<span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>\
								<a href="javascript:void(0)" onclick="Chat.openChat('+chatType+', '+this.user_id+', '+fullName+')" class="m-list-timeline__text">\
									'+online_stat+' '+this.fullname+'\
								</a>\
							</div>');
				});
			}
		}

	},

	/**
	 * online users management
	 */
	onlineUserManager(data){
		console.log(data);
		var online_status = data.online_status;
		var id = data.id;
		if(online_status == 'online'){
			$("#"+id).removeClass('m-badge--secondary');
			$("#"+id).addClass('m-badge--success');
			$("#chat-history-"+id).removeClass('m-badge--secondary');
			$("#chat-history-"+id).addClass('m-badge--success');

		}else if(online_status == 'offline'){
			$("#"+id).removeClass('m-badge--success');
			$("#"+id).addClass('m-badge--secondary');
			$("#chat-history-"+id).removeClass('m-badge--success');
			$("#chat-history-"+id).addClass('m-badge--secondary');
		}
	},

	/**
	 * chat history list
	 * @return {[type]} [description]
	 */
	listChatHistory(){
		var url = apiUrls.chatHistoryList;
		$.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            contentType: "application/json",
            success: Chat.chatHistorySuccess,
            error: Chat.apiError
        });
	},

	/**
	 * chat history list function success
	 * @param  {[type]} result [have value from the backend array list of chat history]
	 */
	chatHistorySuccess(result){
		console.log(result);
		if(result.status === 'success'){
			var data = result.data;
			// var dataLength = data.length;
			var dataLength = Object.size(data);

			console.log(dataLength);
			if(dataLength > 0 ){
				$("#chat-history").html('');
				var chatType = "'from_history'";

				$(data).each(function () {
					console.log(this.unread_count);
					var unread_count = '';
					if((this.unread_count == null) || (this.unread_count == 0)){
						unread_count = '';
					}else{
						unread_count = '<span class="m-badge m-badge--danger">'+this.unread_count+'</span>';
					}

					var online_stat = '<span id="chat-history-'+this.user_id+'" class="m-badge m-badge--secondary"></span>';
					if(this.online_status == 'online'){
						online_stat = '<span id="chat-history-'+this.user_id+'" class="m-badge m-badge--success"></span>';
					}
					if(this.fullname != null){
						var fullName = '`'+this.fullname+'`';
						var user_id = this.user_id;
					}else{
						var fullName = '`'+this.admin_name+'`';
						this.fullname = this.admin_name;
						var user_id = this.admin_id;
					}
					$("#chat-history").append('\
							<div class="m-list-timeline__item">\
								<span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>\
								<a href="javascript:void(0)" onclick="Chat.openChat('+chatType+', '+user_id+', '+fullName+')" class="m-list-timeline__text">\
									'+online_stat+' '+this.fullname+' '+unread_count+'\
								</a>\
							</div>');
				});
			}
		}
	},

	/**
	 * opening a chat with user_id and room_id
	 * @param  {[type]} chatType [contain chatType as new chat or chat from history]
	 * @param  {[type]} id       [user_id or room_id]
	 */
	openChat(chatType, id, fullname){
		console.log(chatType);
		console.log(id);
		$("#open-chat-person").html(fullname);
		this.selectedPerson = id;
		selectedChat = this.selectedPerson;
		$("#message-tab-link").click();
		$("#message-list").html('');
		
		Chat.getMessages(id, 0);
	},

	/**
	 * call ajax for messages
	 */

	getMessages(id, page){
		console.log('getMessages');
		var url = apiUrls.openChat;
		globalPageWidget = page+1;
		$.ajax({
			type:'POST',
			url: url,
			data:{to_id: id, offset: page},
			dataType:'json',
			success: Chat.openChatSuccess,
			error: Chat.apiError
   		});
	},

	/**
	 * open chat success, also printing details to the html
	 */
	openChatSuccess(result){
		console.log(result);
		if(result.status == 'success'){
			var data = result.data;
			var dataLength = data.length;
			scrollFlagWidget = true;
			if(dataLength > 0){
				$(data).each(function(){
					if(this.fullname == null){
						this.fullname = this.admin_name;
					}
					if(this.own_message == 1){
						$("#message-list").prepend('\
							<div class="m-messenger__message m-messenger__message--out">\
								<div class="m-messenger__message-body">\
									<div class="m-messenger__message-arrow"></div>\
									<div class="m-messenger__message-content">\
										<div class="m-messenger__message-text">\
											'+this.pd_msg+'\
										</div>\
										<div><small class="pull-right">'+timeConverter(this.pd_date)+'</small></div>\
									</div>\
								</div>\
							</div>\
							');
					}else{
						$("#message-list").prepend('\
							<div class="m-messenger__message m-messenger__message--in">\
								<div class="m-messenger__message-pic">\
									<img src="assets/app/media/img//users/user3.jpg" alt=""/>\
								</div>\
								<div class="m-messenger__message-body">\
									<div class="m-messenger__message-arrow"></div>\
									<div class="m-messenger__message-content">\
										<div class="m-messenger__message-username">\
											'+this.fullname+'\
										</div>\
										<div class="m-messenger__message-text">\
											'+this.pd_msg+'\
										</div>\
										<div><small class="pull-left">'+timeConverter(this.pd_date)+'</small></div>\
									</div>\
								</div>\
							</div>\
							');
					}

					Chat.markRead(this.pr_id);
					if(globalPageWidget == 1){
						var divHight = $('#message-list')[0].scrollHeight;
						$("#message-list").scrollTop(divHight+100);
					}else{
						$("#message-list").scrollTop(100);
					}
				});
			}
		}else{
			scrollFlagWidget = false;
		}
	},

	markRead(room_id){
		var url = apiUrls.markRead;
		$.ajax({
			type:'POST',
			url: url,
			data:{room_id: room_id, chatType: 'individual'},
			dataType:'json', 
			success:function(data) {
				console.log(data)	       		 
			} // process results here       
		
		 });
	},

	/**
	 * consoling the error of ajax services
	 */
	apiError(error){
		console.log(error);
	},

	/**
	 * send message function
	 */
	doSend(){
		var message = $("#new-message").val();
		var to_id = this.selectedPerson;
		var url =  apiUrls.sendMessage;

		$.ajax({
			type:'POST',
			url: url,
			data:{to_id: to_id, message: message},
			dataType:'json',
			success: Chat.doSendSuccess,
			error: Chat.apiError
   		});
	},

	/**
	 * Success of doSend fucntion
	 * @param  {[type]} result [contain api results]
	 */
	doSendSuccess(result){
		console.log(result);
		if(result.status == 'success'){
			$("#new-message").val('');
			$("#message-list").append('\
							<div class="m-messenger__message m-messenger__message--out">\
								<div class="m-messenger__message-body">\
									<div class="m-messenger__message-arrow"></div>\
									<div class="m-messenger__message-content">\
										<div class="m-messenger__message-text">\
											'+result.pd_msg+'\
										</div>\
										<div><small class="pull-right">'+timeConverter(result.pd_date)+'</small></div>\
									</div>\
								</div>\
							</div>\
							');
			var divHight = $('#message-list')[0].scrollHeight;
			$("#message-list").scrollTop(divHight+100);
		}
	},

	newMessageFromIndividual(data){
		console.log(data);
		console.log('individual');
		console.log(this.selectedPerson);
		console.log(data.sender_id);
		if(this.selectedPerson != data.sender_id){
			return false;
		}

		$("#message-list").append('\
			<div class="m-messenger__message m-messenger__message--in">\
				<div class="m-messenger__message-pic">\
					<img src="assets/app/media/img//users/user3.jpg" alt=""/>\
				</div>\
				<div class="m-messenger__message-body">\
					<div class="m-messenger__message-arrow"></div>\
					<div class="m-messenger__message-content">\
						<div class="m-messenger__message-username">\
							'+data.sender+'\
						</div>\
						<div class="m-messenger__message-text">\
							'+data.message+'\
						</div>\
						<div><small class="pull-left">'+timeConverter(data.chatTime)+'</small></div>\
					</div>\
				</div>\
			</div>\
			');
		var divHight = $('#message-list')[0].scrollHeight;
		$("#message-list").scrollTop(divHight+100);
	},

	logoutFromFcm(){
		var url = apiUrls.logoutFromFcm;
		$.ajax({
			type:'GET',
			url: url,
			dataType:'json',
			success: Chat.logoutSuccess,
			error: Chat.apiError
   		});
	},

	logoutSuccess(result){
		console.log(result);
	},

}

/**
 * api urls for individual chat
 */
var apiUrls = {
	base_url : base_url,
	listAllUsers: this.base_url+"Project_room/listAllUsers", // users list
	openChat: this.base_url+"Project_room/openChat", // open a chat room
	sendMessage: this.base_url+"Project_room/doSendWithIndividual", // send message
	chatHistoryList: this.base_url+"Project_room/chatHistory", // chat history list
	markRead: this.base_url+"Project_room/markReadMessage", // mark messages as read, unread message count managing
	logoutFromFcm: this.base_url+"Project_room/logoutChat", // logout from fcm token
}
$(document).ready(Chat.getAllUsers());
$(document).ready(Chat.listChatHistory());
$(document).on('keyup', "#search-user-widget", Chat.getAllUsers);
$(document).on('click', '.btnpunchout', Chat.logoutFromFcm);

/**
 * common functions 
 */

Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};