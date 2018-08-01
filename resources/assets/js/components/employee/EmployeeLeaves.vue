<template>
  <div class="row">
    <div class="col-xs-12">
      <div v-if='showAlert'>
        <alert :type="alertType">{{ alertText }}</alert>
      </div>
      <div class="box">
          <div class="box-header">
            <div class="box-title">
                  
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
                    <td v-text="runningData.jobs[0]['yearly_leaves']"></td>
                    <td v-text= "runningData.remaining_leaves"></td>
                    <td>
                    <div class="" role="group" aria-label="...">
                       <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                          <button class="btn btn-primary" @click="showleave(runningData.id)" v-show='showAdd'>Show Leaves</button>
                      </transition><br/><br/>
                      <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                          <button class="btn btn-danger" @click="create(runningData.id)" v-show='showAdd'>Apply Leaves</button>
                      </transition>
                    </div>
                  </td>
                  </tr>
               </tbody>
               <tbody  v-else>
                <tr>
                  <td colspan="9">No {{headline}} Available!</td>
                </tr>-
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

     <!-- Modal Apply Leave Window -->
      <div class="modal fade" id="componentDataModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="componentDataModalLabel">{{pupupMod | capitalize}} {{headline}} </h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
              <form @submit.prevent="isNotValidateForm" name="callback">
                
                <div class="form-group">
                  <label>Leaves</label>
                  <select v-model="singleObj.leave_id" class="form-control">
                    <option v-for="leave in leavesList" v-bind:value="leave.leave_id">
                      {{ leave.leave_type | capitalize }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="label-text" class="form-control-label">Reason:</label>
                  <input type="text" class="form-control" name="reason" id="reason" v-model='singleObj.reason'>
                </div>

                <div class="form-group">
                  <label for="label-text" class="form-control-label">Number of Days:</label>
                  <input type="number" class="form-control" name="deducted_leaves" id="deducted_leaves" v-model='singleObj.deducted_leaves'>
                </div>


                <div class="form-group">
                  <label for="label-text" class="form-control-label">Start Date:</label>
                  <div class="input-group date">
                      <input type="text" class="form-control datepicker" name="start_date" id="start_date" value="singleObj.start_date" v-model='singleObj.start_date'>
                      <div class="input-group-addon">
                          <span class="glyphicon glyphicon-th"></span>
                      </div>
                  </div>
                </div>


                <div class="form-group">
                  <label for="label-text" class="form-control-label">End Date:</label>
                  <div class="input-group date">
                      <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="singleObj.end_date" v-model='singleObj.end_date'>
                      <div class="input-group-addon">
                          <span class="glyphicon glyphicon-th"></span>
                      </div>
                  </div>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" :disabled="isNotValidateForm" @click="store()">Add</button>
            </div>
          </div>
        </div>
      </div>
       <!-- Show Modal -->
      <div class="modal fade" id="componentShowModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="componentShowModalLabel">{{pupupMod | capitalize}} {{headline}} </h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
                <div class="box-body table-responsive table-fixed">
                  <table class="table table-hover">
                    <tbody>
                    <tr>
                      <th>Leave Type</th>
                      <th>Reason</th>
                      <th>Leave Days</th>
                      <th>Applied On</th>
                      <th>Approved On</th>
                    </tr>
                    </tbody>
                    <tbody  v-if="componentShowData.length">
                      <tr v-for="runningShowData in componentShowData">
                        <td v-text="runningShowData.leave_type"></td>
                        <td v-text="runningShowData.reason"></td>
                        <td v-text="runningShowData.deducted_leaves"></td>
                        <td v-text="runningShowData.created_at"></td>
                        <td v-text= "runningShowData.updated_at"></td>
                     </tr>
                    </tbody>

                   <tbody  v-else>
                    <tr>
                      <td colspan="5">No {{headline}} Available!</td>
                    </tr>
                   </tbody>
                  </table>
                  
                </div>
                
              
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
                componentShowData:[],
                multiSelection:[],
                isAll:"",
                singleObj:{employee_id:Number,leave_id:Number,deducted_leaves:Number,reason:String,start_date:Date,end_date:Date},
                pagination:{},
                pupupMod:'add',
                showAdd:false,
                showAlert:false,
                alertType:'',
                alertText:'',
                // Component
                gridColumns:['first_name','last_name','email','total_leaves','remaining_leaves','action'],
                escapeSort:['action','total_leaves'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'first_name',order:'asc'},

                LeaveSelected:'',
                leavesList:[]
            };
        },
        mounted() {
            this.all();
            this.leaves();
            this.showAdd=true;
            $('#start_date').datepicker({
                format: 'yyyy-mm-dd'
            });

            $('#end_date').datepicker({
                format: 'yyyy-mm-dd'
            });

            $('#componentDataModal').on('hidden.bs.modal', ()=>this.resetSingleObj());
            $('#componentShowModal').on('hidden.bs.modal');
           
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
            resetSingleObj(){
                this.singleObj={employee_id:"",leave_id:"",deducted_leaves:"",reason:"",start_date:"",end_date:""}; 
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
                let uri=`/employee/leaves?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
                let uri=`/employee/leaves?searchQuery=${searchQuery}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
            create(employee_id){
                this.resetSingleObj();
                this.resetAlert();
                this.pupupMod='add';
                
                this.singleObj.employee_id = employee_id;
                $('#componentDataModal').modal('show');
            },
            showleave(employee_id){
                this.resetSingleObj();
                this.resetAlert();
                this.pupupMod='add';

                let uri='/employee/showallleaves/' + employee_id;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;

                    //console.log(response.data);
                    if(res.status_code==200){
                        this.componentShowData =res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
                //this.singleObj.employee_id = employee_id;
                $('#componentShowModal').modal('show');
            },
            leaves(){
                let uri=`/employee/allleaves`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.leavesList=res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            store(){
                // this.categories.push(this.singleObj);
                this.singleObj.start_date = $('#start_date').val();
                this.singleObj.end_date = $('#end_date').val();
                this.$http.post('/employee/applyleave',this.singleObj).then((response)=>{
                    let res=response.data;
                    if(res.status_code==200){
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
            
            
        },
        computed:{
            isNotValidateForm(){
                if(this.singleObj.deducted_leaves=="" 
                    || this.singleObj.reason=='')
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