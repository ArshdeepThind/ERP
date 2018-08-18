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
                  
                  <td v-text="runningData.product_id"></td>
                  <td v-text="runningData.product_name"></td>
                  <td v-text="runningData.product_price"></td>
                  <td v-text="runningData.quantity_initial"></td>
                  <td v-text="runningData.quantity_shipped"></td>
                  <td v-text="runningData.quantity_left"></td>
                  <td v-text="runningData.product_category"></td>
                  <td v-text="runningData.gender"></td>


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
              <h5 class="modal-title" id="componentDataModalLabel">{{pupupMod}} {{headline}} : {{singleObj.name }}</h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
              <form @submit.prevent="isNotValidateForm" name="callback">
                <div class="form-group">
                  <label for="product_name" class="form-control-label">Product Name</label>
                  <input type="text" class="form-control" id="product_name" v-model='singleObj.product_name'>
                </div>
                <div class="form-group">
                  <label for="product_price" class="form-control-label">Price</label>
                  <input type="text" class="form-control" id="product_price" v-model='singleObj.product_price'>
                </div>

                <div class="form-group">
                  <label for="quantity_initial" class="form-control-label">Initial Quantity</label>
                  <input type="text" class="form-control" id="quantity_initial" v-model='singleObj.quantity_initial'>
                </div>
                <div class="form-group">
                  <label for="quantity_shipped" class="form-control-label">Shipped Quantity</label>
                  <input type="email" class="form-control" id="quantity_shipped" v-model='singleObj.quantity_shipped'>
                </div>

                <div class="form-group">
                  <label for="quantity_left" class="form-control-label">Quantity Left</label>
                  <input type="text" class="form-control" id="quantity_left" v-model='singleObj.quantity_left'>
                </div>
                <div class="form-group">
                  <label id = "product_category">Category</label>
                  <select id = "product_category" v-model="singleObj.product_category" class="form-control">
                    <option value="Clothing"> Clothing </option>
                     <option value="Accessories"> Accessories
                    </option>
                    <option value="Footwear"> Footwear
                    </option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label id = "gender">Gender</label>
                  <select  id = "gender" v-model="singleObj.gender" class="form-control">
                    <option value="Male"> Male </option>
                     <option value="Female"> Female
                    </option>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary" value="submit" @click="update()" v-if="pupupMod=='edit'">Edit</button>
              <button type="submit" class="btn btn-primary" value="submit" @click="store()" v-else>Add</button>
              
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
                singleObj:{product_id:Number,product_name:String,product_price:Number,quantity_initial:Number,quantity_shipped:Number,quantity_left:Number,product_category:String,gender:String},
                pupupMod:'add',
                showAdd:false,
                showAlert:false,
                alertType:'',
                alertText:'',
                // Component
                gridColumns:['Product Id','Name','Price','Initial Quantity','Quantity Shipped','Quantity Left','Category','Gender'],
                escapeSort:['Action'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'product_price',order:'asc'},

                // Module Specific
                roleSelected:'',
                rolesList:[]
            };
        },
        mounted() {
            this.all();
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
                this.singleObj={product_id:"",product_name:"",product_price:"",quantity_initial:"",quantity_shipped:"",quantity_left:"",product_category:"",gender:""}; 
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
                let uri=`/employee/products?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
                let uri=`/employee/products/${obj.product_id}`;
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
                let uri=`/employee/products/removeBulk`;
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

              console.log('here');
                let uri=`/employee/products/${this.singleObj.product_id}`;
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
                this.$http.post('/employee/products',this.singleObj).then((response)=>{
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
              let uri=`/employee/products/status`;
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
              let uri=`/employee/products/statusBulk`;
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
                let uri=`/employee/products?searchQuery=${searchQuery}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
            }
        },
        computed:{
            isNotValidateForm(){
                return true;
            }
        },
        filters: {
        }
    }
</script>