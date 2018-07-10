<template>
    <div class="img-input">
        <img :src="imgsrc" class="img-responsive img-rounded">
        <input @change="onFileChange" id="pic" type="file" class="form-control" name="pic" value="" readonly>
    </div>
</template>
<style>
    

</style>
<script>
    export default {
        props:['imgsrc'],
        mounted() {
            console.log('Component ready.')
        },
        methods:{
            onFileChange(e) {
              var files = e.target.files || e.dataTransfer.files;
              if (!files.length)
                return;
              this.createImage(files[0]);
            },
            createImage(file) {
              var image = new Image();
              var reader = new FileReader();
              var vm = this;

              reader.onload = (e) => {
                this.imgsrc = e.target.result;
              };
              reader.readAsDataURL(file);
            }
        }
    }
</script>
