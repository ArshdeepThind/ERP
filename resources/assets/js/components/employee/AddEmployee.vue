 <template>
 	

 </template>

<style scoped>
    img{
        max-height: 36px;
    }
</style>

 <script type="text/javascript">
 class Errors {

	constructor(){

		this.errors = {};
	}

	get(field)
	{
		if(this.errors[field])
		{
			return this.errors[field][0];
		}
	}

	has(field)
	{
		if(this.errors[field])
		{
			return 1;
		}else
		{
			return 0;
		}
	}

	record(errors)
	{
		$('#loader_logo').hide();
		
		this.errors = errors;
	}

	clear(field)
	{
		
		delete this.errors[field];
	}

	any(){
		return Object.keys(this.errors).length > 0;
	}

}

 export default {
 	props:['employeeids','imgsrc'],
 	mounted() {

 		    var telInput = $("#phone_number");
            telInput.intlTelInput({
                nationalMode: false,
			    preventInvalidNumbers:true,
                utilsScript: "/plugins/intl-tel-input/build/js/utils.js"
            });
            
            $('#dob').datepicker({
			    format: 'yyyy-mm-dd'
			});

            $('#joining_date').datepicker({
			    format: 'yyyy-mm-dd'
			});

			if(this.employeeids!=0)
			{
				this.$http.get('/employee/employees/fetchData/' + this.employeeids)
				.then(response => {
					 // JSON responses are automatically parsed.
					   let res= response.data;

					   this.first_name = res.data.first_name;
					   this.last_name = res.data.last_name;
					   this.phone_number = res.data.phone_number;
					   this.marital_status = res.data.marital_status;
					   this.address1 = res.data.address1;
					   this.address2 = res.data.address2;
					   this.city = res.data.city;
					   this.zipcode = res.data.zipcode;
					   this.state = res.data.state;
					   this.country = res.data.country;
					   this.joining_date = res.data.joining_date;
					   this.current_salary = res.data.current_salary;
					   this.gender = res.data.gender;
					   this.gross_salary = res.data.gross_salary;
					   this.loan_amount = res.data.loan_amount;
					   this.dob = res.data.date_of_birth;
					   this.email = res.data.email;
					   this.job = res.data.jobs[0].job_id;

					   telInput.intlTelInput("setNumber", res.data.phone_number);

				}).catch(e => this.errors.record(error.response.data));	
				
			}
        },

 	data(){

 		return {
 			
 			id: "",
		    first_name: "",
		    last_name: "",
		    email: "",
		    job:"",
		    dob:"",
		 	phone_number:"",
		    pic:this.imgsrc,
		    ispicchange: false,
		    gender:"Male", 
		    password:"",  
		    marital_status:"0",
		    joining_date:"",
		    address1:"",
		    address2:"",
		    zipcode:"",
		    city:"",
		    state:"",
		    country:"",
		    current_salary:"",
		    conversation_id:"",
		    employee_id:"",
		    employee_name:"",
		    gross_salary:"",
		    loan_amount:"",
		    errors: new Errors(),
		    showAlert:false,
	        alertType:'',
	        alertText:'',
	        edit:true,
	        showme:false
	    };
    },

    methods: {

    	onSubmit(id) {

		   var telInput = $("#phone_number");
			if (telInput.val()) {
		    	if (telInput.intlTelInput("isValidNumber")) {
		       		$('.intl-tel-input').find('.colorred').remove();
		        } else {
		            $('.intl-tel-input').find('.colorred').remove();
		            $('.intl-tel-input').append('<div class="colorred">Please enter valid phone number.</div>');
		            return false;
		        }
		    }

		    this.dob = $('#dob').val();
		    this.joining_date = $('#joining_date').val();

    		if(!id)
    		{
    			$('#loader_logo').show();
	    		this.$http.post('/employee/employees', this.$data)
	    		.then(this.onSuccess)
	    		.catch(error => this.errors.record(error.response.data.errors));	
    		}else
    		{
    			$('#loader_logo').show();
    			this.$http.put('/employee/employees/'+id, this.$data)
	    		.then(this.onSuccessUpdate)
	    		.catch(error => this.errors.record(error.response.data.errors));
    		}
    	},
    	onSuccess(response) {
    		this.first_name = "",
		    this.last_name = "",
		    this.email = "",
		    this.dob = "",
		    this.joining_date = "",
		    this.address1 = "",
		    this.address2 = "",
		    this.city = "",
		    this.state = "",
		    this.country = "",
		    this.zipcode = "",
		    this.password = "",
		    this.phone_number ="",
		    this.gross_salary="",
		    this.loan_amount="",
		    this.job="",
		    this.pic = this.imgsrc,
		    $('#dob').val('');
		    $('#joining_date').val('');
	        let conversation_id = response.data.message;
	        let receiverID = response.data.receiverID;
	        console.log(response.data);
	        let send_message = 'Welcome';
	        let detailchat = firebase.database().ref("CHATS/" + conversation_id);
			let time_stamp = firebase.database.ServerValue.TIMESTAMP;
			let newPostKey = detailchat.push().key;
			

				let addchat = firebase
					.database()
					.ref("CHATS/" + conversation_id + "/" + newPostKey);
				
				addchat.set({
					conversation_id: conversation_id,
					messageID: newPostKey,
					messageText: send_message,
					messageType: "text",
					senderID: response.data.senderID,
					senderName: response.data.senderName,
					receiverID:response.data.receiverID,
					receiverName:response.data.receiverName,
					timestamp: time_stamp
				});

			
				let ref = "";
				let ref1 = "";
				let basecount = 0;
				let receiver_basecount = 0;

				ref = firebase.database().ref("RECENTCHAT/" + response.data.senderID + "/" + conversation_id);

				ref.once("value", function(snapshot) {
					basecount = snapshot.val() ? snapshot.val().badgeCount : 1;

						
					ref.set({
						badgeCount: basecount,
						conversation_id: conversation_id,
						messageID: newPostKey,
						messageText: send_message,
						messageType: "text",
						senderID: response.data.senderID,
						senderName: response.data.senderName,
						receiverID:response.data.receiverID,
						receiverName:response.data.receiverName,
						timestamp: time_stamp
					});
				});

				ref1 = firebase.database().ref("RECENTCHAT/" + response.data.receiverID + "/" + conversation_id);

				ref1.once("value", function(snapshot) {
					receiver_basecount = snapshot.val() ? snapshot.val().badgeCount + 1 : 1;
					ref1.set({
						badgeCount: receiver_basecount,
						conversation_id: conversation_id,
						messageID: newPostKey,
						messageText: send_message,
						messageType: "text",
						senderID: response.data.senderID,
						senderName: response.data.senderName,
						receiverID:response.data.receiverID,
						receiverName:response.data.receiverName,
						timestamp: time_stamp
					});
				});

	        this.alertHandler('success','Employee added',true);
	        $('#loader_logo').hide();
    	},
    	onSuccessUpdate(response) {
	        this.alertHandler('success',response.data.message,true);
	        $('#loader_logo').hide();
	    },
        resetAlert(){
            this.alertType='';
            this.alertText='';
            this.showAlert=false;
        },
        alertHandler(type,text,isShow){
            this.alertType=type;
            this.alertText=text;
            this.showAlert=isShow;
            //window.location.href = 'employee/employees';
        },
    	clearjoberror(){
    		this.errors.clear('job');
    	},
        onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.pic = e.target.result;
                vm.ispicchange = true;
            };
            reader.readAsDataURL(file);
        },
        showpasswordfield(){
        	let vm = this;
        	vm.edit = false;
        	vm.showme = true;
        },
        hidepasswordfield(){
        	let vm = this;
        	vm.showme = false;
        	vm.edit = true;
        }
    }

}
</script>