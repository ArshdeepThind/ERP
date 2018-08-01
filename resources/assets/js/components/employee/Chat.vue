<template>
	<div class="row">
		<div v-show="flag_show_chat == 0 && flag_load_chat == 0" class="col-md-12 text-center">
			<h1 class="m-xl-5">No record found</h1>
		</div>

		<div v-show="flag_load_chat == 1" class="col-md-12 text-center">
			<h1 class="m-xl-5">Loading...</h1>
		</div>

		<div v-show="flag_show_chat" class="col-md-4">
			<div class="top">
				<div id="custom-search-form" class="form-search form-horizontal">
					<div class="input-append span12 chat-search">
						<i class="fa fa-search"></i>
						<input type="text" class="search-query-chat" placeholder="Search" v-model="search_case_text" tabindex="-1">
					</div>
				</div>
			</div>
			<div class="scroll-area">
				<div class="dashboard-box-lab" v-for="employeelist_item in filteredCaseList" :key="employeelist_item.conversation_id" @click="displaychat(employeelist_item.conversation_id)" >
					<div class="row bs-example-chat cursor-pointer" :class="employeelist_item.conversation_id == detail_data.conversation_id ? 'bs-example-chat-active' : ''">
						
						<div class="col-md-12">
							<span class="inner-box-lab">#{{ employeelist_item.conversation_id }}</span><br/>
							<span class="black-color-class">Conversation between {{ employeelist_item.senderName }} and {{employeelist_item.receiverName}}</span>
							<span class="chat_badgeCount" :class="employeelist_item.badgeCount > 0 ? '' : 'display-none'">{{ employeelist_item.badgeCount }}</span>
							<br/>
							<span class="inner-box-left-chat-detail">{{ employeelist_item.timestamp | caselist_item_date_format }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div v-show="flag_show_chat" class="col-md-8" id="main">
			<div class="dashboard-box chat-screen-heading">
				<div class="row bs-example-lab-detail-nobackground chat-screen">
					<div class="col-md-3 headerstyle">
						 Chat Window
					</div>
				</div>
			</div>

			<div class="box-body chat-screen">
			<!-- Conversations are loaded here -->
				<div class="direct-chat-messages">
					<div v-for="(message_item, key) in chat_messages" :key="key" style="clear: both;">
						<div v-if="show_chat_messages_date(message_item.timestamp)" class="chat_messages_date_format">{{ message_item.timestamp | message_item_date_format }}</div>
						<div class="direct-chat-msg" :class="message_item.senderID == employee_id ? 'pull-right' : 'receiver'">
							<div v-if="message_item.senderID != employee_id" class="direct-chat-info clearfix">
								<span class="direct-chat-name pull-left">{{ message_item.senderName }}</span>
							</div>
							<div class="direct-chat-text" :class="message_item.senderID == employee_id ? 'sender-chat' : ''">
								
								
									{{ message_item.messageText }}
		
							</div>
							<span class="direct-chat-timestamp" :class="message_item.senderID == employee_id ? 'pull-left' : 'pull-right'">{{ message_item.timestamp | message_item_time_format }}</span>
						</div>
					</div>
				</div>
			</div>

				<div class="row bs-example-chat-detail">
					<div class="col-md-2 form-group">
						<div class="image-upload">
									<label for="file-input" class="cursor-pointer">
										<i class="fa fa-paperclip chat-paperclip" aria-hidden="true"></i>
									</label>
									<form class="display-none">
										<input id="file-input" type="file" @change="sendFilesChange($event.target.files);" />
									</form>
					    </div>
					</div>
					<div class="col-md-10 form-group">
						<div class="fake-input">
							
							<input type="text" name="message" v-model="chatmessage_new" placeholder="Type your message here" class="form-control chat-form">
							<span @click="send()" class="cursor-pointer">
								<img src="/images/send-icon.png">
							</span>
						</div>
						
						<span class="chat_uploading_file_progress small display-none">Uploading in progress...</span>
						<span class="chat_allowed_file_extensions_error small help-block display-none">Only <b>Image</b> (png, jpeg), <b>Video</b> (mp4, mov) file allowed.</span><br />
					</div>
				</div>


			
		</div>

			

	</div>
</template>
<style lang='sass'></style>

<script>

import moment from 'moment';

let chatAllowedFileExtensions = ['image/png', 'image/jpeg', 'video/mp4', 'video/quicktime'];

export default {
	props: ["headline", "employee_id", "employee_name", "chat_employeelist", "flag_load_chat"],

	data() {
		return {
			detail_data: {
				conversation_id: '',
				end_date:'',
			},
			chat_messages: {},
			chatmessage_new: "",
			firebaseStorageRef: firebase.storage().ref(),			
			search_case_text: '',
			flag_show_chat: 0,
			otherdata: []
		};
	},

	mounted() {
		this.chat_messages_date_previous = '';
		this.userTypingTimer = '';
	},


	watch: {
		chat_employeelist: function (val) {
			this.LoadChatCaselist();
		}
	},

	methods: {

		LoadChatCaselist() {
			const vm = this;
			let showchat = '';
			if(vm.chat_employeelist.length) {
				vm.flag_show_chat = 1;
				showchat = vm.chat_employeelist[0].conversation_id;
			}

			if (vm.detail_data.conversation_id == '' && vm.flag_show_chat) {
				vm.displaychat(showchat);
			} else {
				this.removeChatCount(vm.detail_data.conversation_id);
			}

		},

		removeChatCount(showchat) {
			const vm = this;
			
			let ref = firebase.database().ref("RECENTCHAT/" + vm.employee_id + "/" + showchat);
			ref.once("value", function(snapshot) {
				if (snapshot.val()) {
					ref.update({
						badgeCount: 0
					});
				}
			});
		},

		send() {
			const vm = this;
			if (vm.chatmessage_new.trim() == '') {
				return false;
			}

			let conversation_id = vm.detail_data.conversation_id;
			let send_message = vm.chatmessage_new;
			vm.chatmessage_new = '';

			let detailchat = firebase.database().ref("CHATS/" + conversation_id);
			let time_stamp = firebase.database.ServerValue.TIMESTAMP;
			let newPostKey = detailchat.push().key;

			let uri = '/employee/chat/cases/' + conversation_id;
			vm.$http.get(uri).then(response => {
				let res = response.data;
				
					
				let addchat = firebase
					.database()
					.ref("CHATS/" + conversation_id + "/" + newPostKey);
				
				addchat.set({
					conversation_id: conversation_id,
					messageID: newPostKey,
					messageText: send_message,
					messageType: "text",
					senderID: vm.employee_id,
					senderName: vm.employee_name,
					receiverID:res.receiverID,
					receiverName:res.receiverName,
					timestamp: time_stamp
				});

			
				let ref = "";
				let ref1 = "";
				let basecount = 0;
				let receiver_basecount = 0;

				ref = firebase.database().ref("RECENTCHAT/" + vm.employee_id + "/" + conversation_id);

				ref.once("value", function(snapshot) {
					basecount = snapshot.val() ? snapshot.val().badgeCount : 1;

						
					ref.set({
						badgeCount: basecount,
						conversation_id: conversation_id,
						messageID: newPostKey,
						messageText: send_message,
						messageType: "text",
						senderID: vm.employee_id,
						senderName: vm.employee_name,
						receiverID:res.receiverID,
						receiverName:res.receiverName,
						timestamp: time_stamp
					});
				});

				ref1 = firebase.database().ref("RECENTCHAT/" + res.receiverID + "/" + conversation_id);

				ref1.once("value", function(snapshot) {
					receiver_basecount = snapshot.val() ? snapshot.val().badgeCount + 1 : 1;
					ref1.set({
						badgeCount: receiver_basecount,
						conversation_id: conversation_id,
						messageID: newPostKey,
						messageText: send_message,
						messageType: "text",
						senderID: vm.employee_id,
						senderName: vm.employee_name,
						receiverID:res.receiverID,
						receiverName:res.receiverName,
						timestamp: time_stamp
					});
				});
			});
		},




		displaychat(showchat) {
			this.removeChatCount(showchat);
			const vm = this;
			let last_timeStamp = '';
			if (vm.detail_data.conversation_id != '') {
				firebase.database().ref("CHATS/" + vm.detail_data.conversation_id).off();
			}
			vm.detail_data.conversation_id = showchat;
			vm.chat_messages = {};
			vm.chat_messages_date_previous = '';

			let ref = firebase.database().ref("RECENTCHAT/" + vm.employee_id + "/" + showchat);
			ref.once("value", function(snapshot) {
				if (snapshot.val()) {
					let snapshot_data = snapshot.val();
					let last_obj = snapshot_data[Object.keys(snapshot_data).pop()];
					last_timeStamp = last_obj.timestamp;
				}			
			});



				firebase.database().ref("CHATS/" + showchat).orderByChild('timestamp').startAt(last_timeStamp)
				.once("value", function(snapshot) {
					let objChatDiv = $('.direct-chat-messages');

					if (snapshot.val()) {
						let snapshot_data = snapshot.val();
						vm.chat_messages = snapshot_data;


						objChatDiv.parent().css('height', '420px');
						objChatDiv.hide();

						setTimeout(() => {
							objChatDiv.parent().css('height', 'initial');
							objChatDiv.show();
							objChatDiv.scrollTop(objChatDiv.prop("scrollHeight"));
						}, 100);

						vm.$http.post('/employee/chat', {'showchat': showchat, 'snapshot': snapshot_data}).then((response) => {
							})
							.catch((error) => { });
					}

					let child_ref = firebase.database().ref("CHATS/" + showchat).orderByChild('timestamp').startAt(last_timeStamp);
					child_ref.on("child_added", function(snapshot) {
						if (snapshot.val()) {
							let snapshot_data = snapshot.val();
							
							if (snapshot_data.conversation_id == vm.detail_data.conversation_id) {
								let tmp_obj = {};
								tmp_obj[snapshot_data.messageID] = snapshot_data;
								$.extend(vm.chat_messages, tmp_obj);
								//console.log("here");
								setTimeout(() => {
									objChatDiv.scrollTop(objChatDiv.prop("scrollHeight"));
								}, 500);

								vm.$http.post('/employee/chat', {'showchat': showchat, 'snapshot': tmp_obj}).then((response) => {
									})
									.catch((error) => { });
							}
						}
					});
				});
			
		},

		show_chat_messages_date(value) {
			var vm = this;
			let flag = true;
			let date_previous = vm.chat_messages_date_previous;
			let date_now = this.$options.filters.message_item_date_format(value);
			if (date_now == date_previous) {
				flag = false;
			}
			vm.chat_messages_date_previous = this.$options.filters.message_item_date_format(value);

			return flag;
		},

	},

	computed: {
		filteredCaseList: function() {
			var vm = this;
			
			if(vm.search_case_text == "") {
				return vm.chat_employeelist;
			} else {
				return vm.chat_employeelist.filter(function(item) {
					return item.conversation_id.toLowerCase().indexOf(vm.search_case_text.toLowerCase()) >= 0;
				});
			}
		}
	},

	filters: {
		caselist_item_date_format: function (value) {
			if (!value) return '';

			if (moment().format('L') == moment(value).format('L')) {
				return moment(value).format('LT');
			} else if (moment().subtract(1, 'days').format('L') == moment(value).format('L')) {
				return 'Yesterday';
			} else {
				return moment(value).format('L');
			}
		},

		message_item_time_format: function(value) {
			if (!value) return '';
			return moment(value).format('LT');
		},

		message_item_date_format: function(value) {
			if (!value) return '';
			return moment(value).format('LL');
		}
	}


};
</script>