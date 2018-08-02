<template></template>
<style>
    .valid--mail{
        color:#555555;
    }
</style>
<script>
    import FunctionHelper from '../helpers/FunctionHelper.js';
    let funcHelp= new FunctionHelper;
    export default {
        mounted() {
            console.log('Login ready.');
        },
        data(){
            return {
                email:'',
                password:'',
                authUser:{
                    first_name:'',
                    email:""
                }
            };
        },
        computed:{
            hideLoginSubmit(){
                if(this.email=='' || this.password=='' || funcHelp.validateEmail(this.email)==false){
                    return true;
                }
                return false;
            },
            mailClasses(){
                let isValidMail= funcHelp.validateEmail(this.email);
                return {
                    'form-control':true,
                    'valid--mail':isValidMail==true,
                    'invalid--mail':isValidMail==false
                };
            },
            isValidMail(){
                return funcHelp.validateEmail(this.email);
            }
        },
        methods:{
            removeclassfunction(e){
                $('#'+e).parents().removeClass('has-error');
                $('#'+e).nextAll('span:first').remove();
        
            }
        }
    }
</script>