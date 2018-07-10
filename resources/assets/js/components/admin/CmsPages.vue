<template>
  <div class="row">
    <div class="col-xs-12">
      <div v-if='showAlert'>
        <alert :type="alertType">{{ alertText }}</alert>
      </div>
      <div class="box">
          <div class="box-header">
            <div class="box-title">
              
              <div class="pull-right group-actions">
                <button type="button" class="btn btn-danger" 
                  @click='removeBulkConfirm()' title='Delete Selected' 
                  :disabled='multiSelection.length==0'>
                  <i class='fa fa-trash-o' ></i>
                </button>
              
              </div>
              <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                  <a class="btn btn-default pull-right" href='pages/create' v-show='showAdd'>New</a>
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
                  <th><input type="checkbox" value='1' @click="toggleAll()" v-model='isAll'></th>
                  <th v-for="(cols,index) in gridColumns" @click="sortBy(cols,index)">
                  {{ cols }} 
                  <span class="arrow" :class="sortOrder.field == SortingColumns[index] ? sortOrder.order : 'asc'" v-if="escapeSort.indexOf(SortingColumns[index]) < 0"></span>
                  </th>
                </tr>
                </tbody>
                <tbody  v-if="componentData.length">
                  <tr v-for="runningData in componentData">
                    <th>
                      <input type="checkbox" :value="runningData.id" v-model="multiSelection">
                    </th>
                    <td v-text="runningData.title_en"></td>
                    <td v-text="runningData.title_ar"></td>
                    
                    <td v-text="runningData.created_at"></td>
                    <td v-if = "runningData.status==1">
                      <button class="btn btn-primary" 
                        @click="switchStatus(runningData)">
                        Active
                      </button>
                    </td>
                    <td v-else>
                      <button class="btn btn-danger" 
                        @click="switchStatus(runningData)">
                        Inactive
                      </button>
                    </td>
                    <td>
                    <div class="" role="group" aria-label="...">
                       <a type="button" class="btn btn-primary" :href="'pages/'+runningData.id+'/edit'">
                         <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                       </a>
                       <a type="button" class="btn btn-danger" @click="removeConfirm(runningData)">
                         <i class='fa fa-trash-o' ></i>
                       </a>
                    </div>
                  </td>
                  </tr>
               </tbody>
               <tbody  v-else>
                <tr>
                  <td colspan="6">No {{headline}} Available!</td>
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
                gridColumns:['Title(en)','Title(ar)','Created Date','Status','Action'],
                SortingColumns:['title_en','title_ar','created_at','status','Action'],
                escapeSort:['Action'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'title_en',order:'asc'},
            };
        },
        mounted() {
          console.log('hello');
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
              console.log('hello');
                this.resetAlert();
                let uri=`/admin/pages?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.componentData =res.data;
                        this.pagination =res.paginator;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            removeConfirm(obj){
              let confirmBox = new ConfirmBox(this);
              confirmBox.removeBox(this.headline,`You will not be able to recover this ${this.headline}!`, obj);
            },
            remove(obj){
                this.resetAlert();
                var index = this.componentData.indexOf(obj);
                this.componentData.splice(index, 1);
                let uri=`/admin/pages/${obj.id}`;
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
              confirmBox.bulkRemoveBox(this.headline,`You will not be able to recover this ${this.headline}!`);
            },
            removeMultiple(){
                this.resetAlert();
                let uri=`/admin/pages/removeBulk`;
                if(this.multiSelection.length){
                  this.$http.post(uri,this.multiSelection).then((response)=>{
                      let res= response.data;
                      if(res.status_code==200){
                        // Handling alert
                        this.all();
                        this.alertHandler('success',res.message,true);
                      }
                      else{
                        this.alertHandler('error',res.message,true); 
                      }
                  })
                  .catch((error)=>{console.log(error)});
                }
            },
            switchStatus(obj){
              this.resetAlert();
              let newStat=(obj.status=='1')?'0':'1';
              let uri=`/admin/pages/status`;
              this.$http.put(uri,obj).then((response)=>{
                  let res= response.data;
                  if(res.status_code==200){
                    // Handling alert
                     obj.status=newStat;
                    this.alertHandler('success',res.message,true);
                  }
              })
              .catch((error)=>{});
            },
            switchStatusBulkConfirm(){
              let confirmBox = new ConfirmBox(this);
              confirmBox
                .bulkStatusBox(this.headline,`You will be able to restore selected ${this.headline} state!`);
            },
            switchStatusSelected(){
              this.resetAlert();
              let uri=`/admin/pages/statusBulk`;
              this.$http.put(uri,this.multiSelection).then((response)=>{
                  let res= response.data;
                  if(res.status_code==200){
                    this.all();
                    this.resetMultiSelection();
                    // Handling alert
                    this.alertHandler('success',res.message,true);
                  }
              })
              .catch((error)=>{});
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
            sortBy(cols,index){
              if(this.escapeSort.indexOf(this.SortingColumns[index]) < 0 ){
                if(this.SortingColumns[index] == this.sortOrder.field){
                    this.sortOrder.order= (this.sortOrder.order=='asc')? 'desc':'asc';
                }
                else{
                    this.sortOrder= {field:this.SortingColumns[index],order:'asc'} ; 
                }
                this.all(this.pagination.current_page);
              }
            },
            searchInput(){
                let searchQuery=this.searchQuery;
                let uri=`/admin/pages?searchQuery=${searchQuery}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
            
        }
    }
</script>