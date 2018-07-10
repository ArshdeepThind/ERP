<template>
  <div class="row">
    <div class="col-xs-12">
      <div v-if='showAlert'>
        <alert :type="alertType">{{ alertText }}</alert>
      </div>
      <div class="box">
        <div class="box-header">
          <!-- ADD EVENT -->
          <div class="box-title">
            <div class="pull-right group-actions">
              <button type="button" class="btn btn-danger" 
                @click='removeBulkConfirm()' title='Delete Selected' 
                :disabled='multiSelection.length==0'>
                <i class='fa fa-trash-o' ></i>
              </button>
            </div>
            <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                <button class="btn btn-default pull-right" @click="create()" v-show='showAdd'>New</button>
            </transition>
          </div>
          <div class="box-tools">
            <form class="form-inline" @submit.prevent="searchInput">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon"><span class="glyphicon glyphicon-search"></span></div>
                  <input type="text" v-model="searchQuery" class="form-control" id="exampleInputAmount" placeholder="Search" @keyup.delete="searchChanges">
                </div>
              </div>
            </form>
          </div>
        </div>
          <!-- /.box-header -->
        <div class="box-body table-responsive table-fixed">
          <table class="table table-hover">
            <tbody>
                <tr>
                  <th><input type="checkbox" value='1' @click="toggleAll()" v-model='isAll'></th>
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
                  <th>
                    <input type="checkbox" :value="runningData.id" v-model="multiSelection">
                  </th>
                  <td v-text="runningData.name"></td>
                  <td v-text="runningData.email"></td>
                  <td v-text="runningData.inrole"></td>
                  <td>
                    <button :class="runningData.status=='active'?'btn btn-primary':'btn btn-danger'" 
                      @click="switchStatus(runningData)">
                      {{runningData.status | capitalize}} 
                    </button>
                  </td>
                  <td>
                    <div class="" role="group" aria-label="...">
                       <button type="button" class="btn btn-primary" @click="show(runningData)">
                         <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                       </button>
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
      <!-- Modal Window -->
      <div class="modal fade" id="componentDataModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="componentDataModalLabel">{{pupupMod | capitalize}} {{headline}} : {{singleObj.name }}</h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
              <form @submit.prevent="isNotValidateForm" name="callback">
                <div class="form-group">
                  <label for="role-name" class="form-control-label">Name:</label>
                  <input type="text" class="form-control" id="role-name" v-model='singleObj.name'>
                </div>
                <div class="form-group">
                  <label for="label-text" class="form-control-label">Email:</label>
                  <input type="email" class="form-control" id="label-name" v-model='singleObj.email'>
                </div>
                <div class="form-group">
                  <label>Assign Role</label>
                  <select v-model="singleObj.inrole" class="form-control">
                    <option v-for="role in rolesList" v-bind:value="role.name">
                      {{ role.name | capitalize }}
                    </option>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" :disabled="isNotValidateForm" @click="update()" v-if="pupupMod=='edit'">Edit</button>
              <button type="button" class="btn btn-primary" :disabled="isNotValidateForm" @click="store()" v-else>Add</button>
            </div>
          </div>
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
                singleObj:{id:Number,name:String,email:String,status:String,inrole:String},
                pupupMod:'add',
                showAdd:false,
                showAlert:false,
                alertType:'',
                alertText:'',
                // Component
                gridColumns:['Name','Email','Role','Status','Action'],
                escapeSort:['Action'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'name',order:'asc'},

                // Module Specific
                roleSelected:'',
                rolesList:[]
            };
        },
        mounted() {
            this.all();
            this.roles();
            this.showAdd=true;
            $('#componentDataModal').on('hidden.bs.modal', ()=>this.resetSingleObj());
            let cats=this.componentData;
        },
        methods:{
            resetMultiSelection(){
              this.multiSelection=[];
              this.isAll="";
            },
            resetSingleObj(){
                this.singleObj={id:"",name:"",email:"",status:"",inrole:""}; 
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
                let uri=`/admin/administrator?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.componentData =res.data;
                        this.pagination =res.paginator;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            show(obj){
                this.pupupMod='edit';
                this.resetAlert();
                this.singleObj=obj;
                $('#componentDataModal').modal('show');
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
                let uri=`/admin/administrator/${obj.id}`;
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
                .bulkRemoveBox(this.headline,`You will not be able to recover this ${this.headline}!`);
            },
            removeMultiple(){
                this.resetAlert();
                let uri=`/admin/administrator/removeBulk`;
                if(this.multiSelection.length){
                  this.$http.post(uri,this.multiSelection).then((response)=>{
                      let res= response.data;
                      if(res.status_code==200){
                        // Handling alert
                        this.resetMultiSelection();
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
            update(){
                let uri=`/admin/administrator/${this.singleObj.id}`;
                this.$http.put(uri,this.singleObj).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                      // Handling alert
                      this.alertHandler('success',res.message,true);
                    }
                    else{
                      this.alertHandler('error',res.message,true); 
                    }
                    $('#componentDataModal').modal('hide');
                })
                .catch((error)=>{});
            },
            create(){
                this.resetSingleObj();
                this.resetAlert();
                this.pupupMod='add';
                $('#componentDataModal').modal('show');
            },
            store(){
                // this.categories.push(this.singleObj);
                this.$http.post('/admin/administrator',this.singleObj).then((response)=>{
                    let res=response.data;
                    if(res.status_code==201){
                        this.resetSingleObj(); // reset store input form
                        this.all(); // fetch updated list
                        $('#componentDataModal').modal('hide'); // Hide modal
                        // Handling alert
                        this.alertHandler('success',res.message,true);
                    }
                    else{
                      this.alertHandler('error',res.message,true);
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            switchStatus(obj){
              this.resetAlert();
              let newStat=(obj.status=='active')?'inactive':'active';
              let uri=`/admin/administrator/status`;
              this.$http.put(uri,obj).then((response)=>{
                  let res= response.data;
                  if(res.status_code==200){
                    // Handling alert
                     obj.status=newStat;
                    this.alertHandler('success',res.message,true);
                  }
                  $('#componentDataModal').modal('hide');
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
              let uri=`/admin/administrator/statusBulk`;
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
                let uri=`/admin/administrator?searchQuery=${searchQuery}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
            // Module Specific
            roles(){
                let uri=`/admin/roles/all`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.rolesList=res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
        },
        computed:{
            isNotValidateForm(){
                if(this.singleObj.name=="" 
                    || this.singleObj.email=='' 
                    || funcHelp.validateEmail(this.singleObj.email)==false)
                {
                    return true;
                }
                return false;
            }
        },
        filters: {
            capitalize(str) {
              return str.charAt(0).toUpperCase() + str.slice(1)
            }
        }
    }
</script>