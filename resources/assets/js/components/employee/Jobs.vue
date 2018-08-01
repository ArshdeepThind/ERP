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
            <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                <button class="btn btn-primary" @click="create()" v-show='showAdd'>New</button>
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
                  <td v-text="runningData.job_title"></td>
                  <td v-text="runningData.job_description"></td>
                  <td v-text="runningData.no_of_vacancies"></td>
                  <td v-text="runningData.experience_required"></td>
                  <td v-text="runningData.department_name"></td>
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
              <h5 class="modal-title" id="componentDataModalLabel">{{pupupMod | capitalize}} {{headline}} : {{singleObj.job_title }}</h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
              <form @submit.prevent="isNotValidateForm" name="callback">
                <div class="form-group">
                  <label for="role-name" class="form-control-label">Job Title:</label>
                  <input type="text" class="form-control" name="job_title" id="job_title" v-model='singleObj.job_title'>
                </div>
                <div class="form-group">
                  <label for="label-text" class="form-control-label">Job Description:</label>
                  <input type="text" class="form-control" name="job_description" id="job_description" v-model='singleObj.job_description'>
                </div>

                <div class="form-group">
                  <label for="label-text" class="form-control-label">Experience Required:</label>
                  <input type="number" class="form-control" name="experience_required" id="experience_required" v-model='singleObj.experience_required'>
                </div>


                <div class="form-group">
                  <label for="label-text" class="form-control-label">Vacancies:</label>
                  <input type="number" class="form-control" name="no_of_vacancies" id="no_of_vacancies" v-model='singleObj.no_of_vacancies'>
                </div>
                
                <div class="form-group">
                  <label for="label-text" class="form-control-label">Yearly Leaves:</label>
                  <input type="number" class="form-control" name="yearly_leaves" id="yearly_leaves" v-model='singleObj.yearly_leaves'>
                </div>

                <div class="form-group">
                  <label>Assign Department</label>
                  <select v-model="singleObj.department_id" class="form-control">
                    <option v-for="department in departmentList" v-bind:value="department.id">
                      {{ department.name | capitalize }}
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
                singleObj:{job_id:Number,job_title:String,job_description:String,experience_required:Number,no_of_vacancies:Number,department_id:Number,yearly_leaves:Number},
                pupupMod:'add',
                showAdd:false,
                showAlert:false,
                alertType:'',
                alertText:'',
                // Component
                gridColumns:['job_title','job_description','#vacancies','experience','department_name','Action'],
                escapeSort:['Action','#vacancies','experience','job_description'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'job_title',order:'asc'},

                // Module Specific
                departmentSelected:'',
                departmentList:[]
            };
        },
        mounted() {
            this.all();
            this.departments();
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
                this.singleObj={job_id:"",job_title:"",job_description:"",experience_required:"",no_of_vacancies:"",department_id:""}; 
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
                let uri=`/employee/job?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
                let uri=`/employee/job/${obj.job_id}`;
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
            update(){
                let uri=`/employee/job/${this.singleObj.job_id}`;
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
                this.$http.post('/employee/job',this.singleObj).then((response)=>{
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
                let uri=`/employee/job?searchQuery=${searchQuery}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
            departments(){
                let uri=`/employee/job/departments`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.departmentList=res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
        },
        computed:{
            isNotValidateForm(){
                if(this.singleObj.job_title=="" 
                    || this.singleObj.job_description==''|| this.singleObj.no_of_vacancies==''|| this.singleObj.experience_required=='')
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