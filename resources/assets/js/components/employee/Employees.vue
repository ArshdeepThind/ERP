<template>
  <div class="row">
    <div class="col-xs-12">
      <div v-if='showAlert'>
        <alert :type="alertType">{{ alertText }}</alert>
      </div>
      <div class="box">
          <div class="box-header">
            <div class="box-title">
                  
                  <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                      <a class="btn btn-primary" href='employees/create' v-show='showAdd'>New</a>
                  </transition>
            </div>
            <div class="box-tools">
                <form class="form-inline" @submit.prevent="searchInput">
                <div class="input-group input-group-sm" style="width: 200px;">
                  <input type="text" v-model="searchQuery" name="table_search" class="form-control pull-right" placeholder="Search" id="exampleInputAmount" @keyup.delete="searchChanges">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>
            </div>
          </div>
          <div class="box-body table-responsive table-fixed">
              <table class="table table-hover">
               <tbody>
                <tr>
                  <th v-for="(cols,index) in gridColumns" @click="sortBy(cols)">
                  {{ cols }} 
                  <span class="arrow" 
                    :class="sortOrder.field == cols ? sortOrder.order : 'asc'"
                    v-if="escapeSort.indexOf(cols) < 0"></span>
                  </th>
                </tr>
                </tbody>
                <tbody  v-if="componentData.length">
                  <tr v-for="runningData in componentData">
                    <td v-text="runningData.first_name"></td>
                    <td v-text="runningData.last_name"></td>
                    <td v-text="runningData.email"></td>
                    <td v-text="runningData.phone_number"></td>
                    <td>${{runningData.current_salary}}</td>
                    <td>
                    <div class="" role="group" aria-label="...">
                       <a type="button" class="btn btn-primary btn-round" :href="'employees/'+runningData.id+'/edit'">
                         <i class="fa fa-pencil-square-o"></i>
                       </a>
                       <a type="button" class="btn btn-danger btn-round" @click="removeConfirm(runningData)">
                         <i class='fa fa-trash-o'></i>
                       </a><br/><br/>
                      <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                          <a class="btn btn-primary" :href="'payment/paymenthistory/'+runningData.id+'/'">Payment History</a>
                      </transition>
                    </div>
                  </td>
                  </tr>
               </tbody>
               <tbody  v-else>
                <tr>
                  <td colspan="9">No {{headline}} Available!</td>
                </tr>
               </tbody>
              </table>
          </div>
          <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li>
                  <a href="javascript:void(0);" aria-label="Previous" @click="prevPage()"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <li v-for="n in pagination.total_pages" 
                  :class="{'active':pagination.current_page==n}">
                  <a href="javascript:void(0);" @click="all(n)">{{ n }}</a>
                </li>
                <li>
                  <a href="javascript:void(0);" aria-label="Next" @click="nextPage()">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
          </div>
      </div>
    </div>
  </div>
</template>
<style lang='sass'></style>
<script>
    import FunctionHelper from '../../helpers/FunctionHelper.js';
    import ConfirmBox from '../../helpers/ConfirmBox.js';
    let funcHelp= new FunctionHelper;
    
    export default {
        props:['headline'],
        data(){
            return {
                componentData:[],
                multiSelection:[],
                isAll:"",
                pagination:{},
                pupupMod:'add',
                showAdd:false,
                showAlert:false,
                alertType:'',
                alertText:'',
                // Component
                gridColumns:['first_name','last_name','email','phone','current salary','action'],
                escapeSort:['current salary','action','phone'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'first_name',order:'asc'},
            };
        },
        mounted() {
            this.all();
            this.showAdd=true;
        },
        methods:{
            resetMultiSelection(){
              this.multiSelection=[];
              this.isAll="";
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
            toggleAll(){
              if(this.isAll==true){
                this.componentData.map((ele)=>{
                  this.multiSelection.push(ele.id);
                })
              }
              else{
                this.componentData.map((ele)=>{
                  this.multiSelection.pop(ele.id);
                })
              }
            },
            all(page=1){
                this.resetAlert();
                let uri=`/employee/employees?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;

                    console.log(response.data);
                    if(res.status_code==200){
                        this.componentData =res.data;
                        this.pagination =res.paginator;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            removeConfirm(obj){
              let confirmBox = new ConfirmBox(this);
              confirmBox
                .removeBox(this.headline,`You will not be able to recover this ${this.headline}!`, obj);
            },
            remove(obj){
                this.resetAlert();
                var index = this.componentData.indexOf(obj);
                this.componentData.splice(index, 1);
                let uri=`/employee/employees/${obj.id}`;
                this.$http.delete(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                      // Handling alert
                      this.alertHandler('success',res.message,true);
                    }
                    else{
                      this.alertHandler('error',res.message,true); 
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            removeBulkConfirm(){
              let confirmBox = new ConfirmBox(this);
              confirmBox
                .bulkRemoveBox(this.headline,`You will not be able to recover selected ${this.headline}!`);
            },
            removeMultiple(){
                this.resetAlert();
                let uri=`/employee/employees/removeBulk`;
                if(this.multiSelection.length){
                  this.$http.post(uri,this.multiSelection).then((response)=>{
                      let res= response.data;
                      if(res.status_code==200){
                        // Handling alert
                        this.all();
                        this.resetMultiSelection();
                        this.alertHandler('success',res.message,true);
                      }
                      else{
                        this.alertHandler('error',res.message,true); 
                      }
                  })
                  .catch((error)=>{console.log(error)});
                }
            },
            // Pagination scoping
            nextPage(){
                let pagination=this.pagination;
                if(pagination.current_page < pagination.total_pages){
                    let reqPage=pagination.current_page+1;
                    this.all(reqPage);
                }
            },
            prevPage(){
                let pagination=this.pagination;
                if(pagination.current_page > 1){
                    let reqPage=pagination.current_page - 1;
                    this.all(reqPage);
                }
            },
            sortBy(cols){
              if(this.escapeSort.indexOf(cols) < 0 ){
                if(cols == this.sortOrder.field){
                    this.sortOrder.order= (this.sortOrder.order=='asc')? 'desc':'asc';
                }
                else{
                    this.sortOrder= {field:cols,order:'asc'} ; 
                }
                this.all(this.pagination.current_page);
              }
            },
            searchInput(){
                let searchQuery=this.searchQuery;
                let uri=`/employee/employees?searchQuery=${searchQuery}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.componentData =res.data;
                        this.pagination =res.paginator;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            searchChanges(){
                let searchQuery=this.searchQuery;
                if(searchQuery==""){
                    this.all();
                }
            },
            
        },
        computed:{},
        filters: {
            capitalize(str) {
              return str.charAt(0).toUpperCase() + str.slice(1)
            }
        }
    }
</script>