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
            </div>
          </div>
          <div class="box-body table-responsive table-fixed">
              <table class="table table-hover">
               <tbody>
                <tr>
                  <th v-for="(cols,index) in gridColumns" @click="sortBy(sortColumns[index])">
                  {{ gridColumns[index] }} 
                  <span class="arrow" 
                    :class="sortOrder.field == sortColumns[index] ? sortOrder.order : 'asc'"
                    v-if="escapeSort.indexOf(sortColumns[index]) < 0"></span>
                  </th>
                </tr>
                </tbody>
                <tbody  v-if="componentData.length">
                  <tr v-for="runningData in componentData">
                    <td class="fontweight">{{runningData.year_salary}}<br/>{{runningData.month_salary}}</td>
                    <td><b>${{runningData.net_salary}}</b><br/>Salary Calculation:<br/>Base Salary : ${{runningData.base_salary_month}}<br/>Gross Salary : ${{runningData.gross_salary_month}}<br/>Deduction : ${{runningData.total_salary_deducted}}</td>
                    <td v-text="runningData.total_days_worked"></td>
                    <td v-text="runningData.total_leaves"></td>
                    <td v-text= "runningData.total_non_paid_leaves"></td>
                    <td v-text= "runningData.paying_date"></td>
                    <td>
                    
                       <transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                          <button class="btn btn-primary" @click="showslip(runningData)">Salary Slip</button>
                      </transition>
                    
                  </td>
                  </tr>
               </tbody>
               <tbody  v-else>
                <tr>
                  <td colspan="7">No {{headline}} Available!</td>
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

       <!-- Show Modal -->
       
      <div class="modal fade" id="componentShowModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="componentShowModalLabel">PaySlip# {{singleObj.id}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>

            </div>
            <div id="printThis">
            <div class="modal-body">
                <div class="box-body table-responsive table-fixed">
                  <p align="center" class="fontweight"><b>ERP ORGANIZATION</b></p>	
                  <table class="table table-hover">

        			 <tr>
        			 	<td width="12%"><b>PaySlip# :</b></td>
        			 	<td width="12%">{{singleObj.id}}</td>
        			 	<td width="19%"><b>Payslip for the month :</b></td>
        			 	<td width="19%"><b>{{singleObj.month_salary}}, {{singleObj.year_salary}}</b></td>
        			 	<td width="12%"><b>Branch :</b></td>
        			 	<td>Windsor</td>
        			 </tr>
        			 <tr>
        			 	<td width="12%"><b>Employee Code :</b></td>
        			 	<td width="12%">{{singleObj.employee_id}}</td>
        			 	<td width="19%"><b>Employee Name :</b></td>
        			 	<td width="19%">{{singleObj.employee_name}}</td>
        			 	<td width="12%"><b>Designation :</b></td>
        			 	<td width="12%">{{singleObj.designation}}</td>
        			 </tr>
        			 <tr>
        			 	<td width="12%"><b>Department :</b></td>
        			 	<td width="12%">{{singleObj.department_name}}</td>
        			 </tr>
        			 <hr class="payhr">	            
        			 <tr>
        			 	<td width="12%"><b>Days Paid :</b></td>
        			 	<td width="12%">{{singleObj.total_days_worked}}</td>
        			 	<td width="19%"><b>Days Present :</b></td>
        			 	<td width="19%">{{singleObj.days_present}}</td>
        			 </tr>
        			 <tr>
        			 	<td width="12%"><b>Paid Leaves :</b></td>
        			 	<td width="12%">{{singleObj.total_leaves}}</td>
        			 	<td width="19%"><b>Unpaid Leaves :</b></td>
        			 	<td width="19%">{{singleObj.total_non_paid_leaves}}</td>
        			 </tr>
        			 <tr>
        			 	<td width="12%"><b>Leave Balance :</b></td>
        			 	<td width="12%">{{singleObj.remaining_leaves}}</td>
        			 </tr>

        			 <tr>
        			 	<td width="12%"><b>Paid On :</b></td>
        			 	<td width="12%">{{singleObj.paying_date}}</td>
        			 </tr>
        			<hr class="payhr">	            
        				<tr>
        					<td colspan="6" align="center"><b>REGULAR EARNING</b></td>
        				</tr>
        			<hr class="payhr">
	        			<tr>
	        				<td width="12%"><b>Base Pay :</b></td>
	        			 	<td width="12%">${{singleObj.base_salary_month}}</td>
	        				<td width="12%"><b>Base pay rate :</b></td>
	        			 	<td width="12%">{{singleObj.current_salary}}</td>
	        			 	<td width="10%"><b>Total hours :</b></td>
	        			 	<td width="10%">{{singleObj.total_hours}}</td>
	        			</tr>
        			 	<tr>
        			 		<td width="12%"><b>Gross Pay :</b></td>
        			 		<td width="12%">${{singleObj.gross_salary_month}}</td>
        			 		<td width="12%"><b>Gross pay rate :</b></td>
        			 		<td width="12%">{{singleObj.gross_salary}}</td>
        			 		<td width="10%"><b>Total hours :</b></td>
        			 		<td width="10%">{{singleObj.total_hours}}</td>
        			 	</tr>
	        		<hr class="smallpayhr"> 
	        			 <tr>
	        			 	<td width="12%"><b>EARNING :</b></td>
	        			 	<td width="12%">${{singleObj.earning}}</td>
	        			 </tr>
	        		<hr class="payhr"> 
	        			<tr>
        					<td colspan="6" align="center"><b>DEDUCTIONS</b></td>
        				</tr>
        			<hr class="payhr">
	        			<tr>
	        				<td width="12%"><b>Professional Tax :</b></td>
	        			 	<td width="12%">${{singleObj.professional_tax}}</td>
	        			</tr>
        			 	<tr>
        			 		<td width="12%"><b>ESI :</b></td>
	        			 	<td width="12%">${{singleObj.esi}}</td>
	        		 	</tr>
	        		 	<tr>
	        		 		<td width="12%"><b>Loan :</b></td>
	        			 	<td width="12%">${{singleObj.loan_amount}}</td>
	        		 	</tr>
	        		<hr class="smallpayhr"> 
	        			 <tr>
	        			 	<td width="12%"><b>DEDUCTION :</b></td>
	        			 	<td width="12%">${{singleObj.total_salary_deducted}}</td>
	        			 </tr>
	        		<hr class="payhr">
	        			<tr>
	        			 	<td width="12%"><b>NET PAY :</b></td>
	        			 	<td width="12%">${{singleObj.net_salary}}</td>
	        			</tr>
                  </table>
                </div>
            </div>
			</div>
            <div class="modal-footer">
		       <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		       <button id="btnPrint" type="button" class="btn btn-primary">Print</button>
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
        props:['headline','employee_id','professional_tax','esi'],
        data(){
            return {
                componentData:[],
                multiSelection:[],
                isAll:"",
                singleObj:{id:Number,employee_id:Number,employee_name:String,year_salary:String,month_salary:String,department:String,designation:String,net_salary:Number,days_present:Number,base_salary_month:Number,gross_salary_month:Number,total_salary_deducted:Number,total_days_worked:Number,total_leaves:Number,total_non_paid_leaves:Number,paying_date:String,total_hours:Number,earning:Number,professional_tax:Number,esi:Number},
                pagination:{},
                pupupMod:'add',
                showAdd:false,
                showAlert:false,
                alertType:'',
                alertText:'',
                // Component
                sortColumns:['year_salary','net_salary','total_days_worked','total_leaves','total_non_paid_leaves','paying_date','Payslip'],
                gridColumns:['Year/Month','Salary Calculation','Total Working Days','Paid Leaves','Unpaid Leaves','Paid On','Payslip'],
                escapeSort:['net_salary','Payslip'],
                gridAction:[
                    {title:'edit',fire:"edit"},
                    {title:'delete',fire:"delete"},
                    {title:'force',fire:"force"}
                ],
                searchQuery:'',
                sortOrder:{field:'year_salary',order:'asc'},
            };
        },
        mounted() {
            this.all();
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
                this.singleObj={id:"",employee_id:"",employee_name:"",year_salary:"",month_salary:"",department_name:"",designation:"",net_salary:"",base_salary_month:"",gross_salary_month:"",total_salary_deducted:"",total_days_worked:"",total_leaves:"",total_non_paid_leaves:"",paying_date:"",days_present:"",total_hours:"",earning:"",professional_tax:"",esi:""}; 
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
                let uri=`/employee/payment/paymenthistory/${this.employee_id}?page=${page}&sort=${this.sortOrder.field}&order=${this.sortOrder.order}`;
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
            showslip(obj){
                this.resetSingleObj();
                this.resetAlert();
                this.pupupMod='add';
                

                let uri='/employee/payment/showpayslip/'+obj.id;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;

                    console.log(response.data);
                    if(res.status_code==200){
                        this.singleObj =res.data[0];
                        this.singleObj.employee_name = res.data[0].first_name + " " +res.data[0].last_name;
                        this.singleObj.days_present = res.data[0].total_days_worked - res.data[0].total_leaves - res.data[0].total_non_paid_leaves;
                        this.singleObj.total_hours = 8 * res.data[0].days_present;
                        this.singleObj.earning = res.data[0].base_salary_month + res.data[0].gross_salary_month;
                        this.singleObj.professional_tax = this.professional_tax;
                        this.singleObj.esi = this.esi;
                    }
                })
                .catch((error)=>{console.log(error)});
                
                $('#componentShowModal').modal('show');
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