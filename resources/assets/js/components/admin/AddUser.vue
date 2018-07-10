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
		console.log(field);
		delete this.errors[field];
	}

	any(){
		return Object.keys(this.errors).length > 0;
	}

}

 export default {
 	props:['userids','imgsrc'],
 	mounted() {

 		   var telInput = $("#phone");
            telInput.intlTelInput({
                nationalMode: false,
			    preventInvalidNumbers:true,
                utilsScript: "/plugins/intl-tel-input/build/js/utils.js"
            });
            
            $('#dob').datepicker({
			    format: 'yyyy-mm-dd'
			});


           
          
			if(this.userids!=0)
			{
				this.$http.get('/admin/users/fetchData/' + this.userids)
				.then(response => {
					 // JSON responses are automatically parsed.
					   let res= response.data;

					   this.firstname = res.data.firstname;
					   this.lastname = res.data.lastname;
					   this.phone = res.data.phone;
					   this.gender = res.data.gender;
					   this.dob = res.data.dob;
					   this.email = res.data.email;

					   telInput.intlTelInput("setNumber", res.data.phone);

				}).catch(e => this.errors.record(error.response.data));	
				
			}
        },

 	data(){

 		return {
 			
 			id: "",
		    firstname: "",
		    lastname: "",
		    email: "",
		    dob:"",
		    phone:"",
		    pic:this.imgsrc,
		    ispicchange: false,
		    gender:"Male",   
		    errors: new Errors(),
		    showAlert:false,
	        alertType:'',
	        alertText:'',
	    };
    },

    methods: {

    	onSubmit(id) {

		   var telInput = $("#phone");
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

    		if(!id)
    		{
    			$('#loader_logo').show();
	    		this.$http.post('/admin/users', this.$data)
	    		.then(this.onSuccess)
	    		.catch(error => this.errors.record(error.response.data));	
    		}else
    		{
    			$('#loader_logo').show();
    			this.$http.put('/admin/users/'+id, this.$data)
	    		.then(this.onSuccessUpdate)
	    		.catch(error => this.errors.record(error.response.data));	
    		}
    	},
    	onSuccess(response) {
    		this.firstname = "",
		    this.lastname = "",
		    this.email = "",
		    this.dob12 = "",
		    this.phone ="",
		    this.pic = this.imgsrc,
		    $('#dob').val('');
	        this.alertHandler('success',response.data.message,true);
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
        }
    }

 }
 </script>