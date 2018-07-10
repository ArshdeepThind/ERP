<template>
    <div :class="alertClasses" v-show="show">
        <slot></slot>
        <span class="Alert__close" @click="show=false">x</span>
    </div>    
</template>

<script>
    export default {
        props:['type'],
        data(){
            return {
                show:true
            };
        },
        mounted() {
            console.log('Component ready.')
        },
        computed:{
           alertClasses(){
                var  type= this.type;
                return {
                    'Alert':true,
                    'alert':true,
                    'alert-success': type=='success',
                    'alert-danger': type=='error',
                    'alert-info': type=='info',
                    'alert-warning': type=='warning',
                }
           } 
        }
    }
</script>
