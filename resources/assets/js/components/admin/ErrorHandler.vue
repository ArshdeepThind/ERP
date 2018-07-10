<template></template>
<style>
    .valid--mail{
        color:#555555;
    }
    
   
</style>
<script>
    import FunctionHelper from '../../helpers/FunctionHelper.js';
    let funcHelp= new FunctionHelper;
    export default {
        mounted() {
            console.log('Login ready.')
        },
        data(){
            return {
               
            };
        },
        methods:{
            removeclassfunction(e){
                $('#'+e).parents().removeClass('has-error');
                $('#'+e).nextAll('span:first').remove();
            }
            
        }
    }
</script>