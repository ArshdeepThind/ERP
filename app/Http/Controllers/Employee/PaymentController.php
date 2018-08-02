<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Payment;
use App\Setting;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'first_name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            $employees = Employee::whereHas('in_process_salary',function($query) use ($search)
            {
                if ($search) {
                    $query->where('first_name','like',"$search%")
                        ->orWhere('last_name','like',"$search%")
                        ->orWhere('email','like',"$search%");
                }
            })->with('in_process_salary')
            ->orderBy("$sort", "$order")->paginate(10);
           
            $paginator=[
                'total_count'  => $employees->total(),
                'total_pages'  => $employees->lastPage(),
                'current_page' => $employees->currentPage(),
                'limit'        => $employees->perPage()
            ];
            return response([
                "data"        =>$employees->all(),
                "paginator"   =>$paginator,
                "status_code" =>200
            ],200);

            
        }
        return view('employee.payment.list');         
    }

    public function changestatus(Request $request){
        
        $validator = validator()->make($request->all(), [
            'id'   =>'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        $employee_salary= Payment::find($id);
        if($employee_salary->count() > 0){
            $employee_salary->is_paid = 'paid';
            $employee_salary->save();
            // Get New updated Object of Admin
           
            if($request->wantsJson()){
                return response([
                    "message"     =>trans('Status Changed'),
                    "status_code" =>200
                ],200);
            }
            flash(trans('Status Changed'),'success');
            return back();
        }
        
        return back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function paymenthistory($id){
        //echo $id;

        $employee_id = $id;
        $esi = Setting::find(3)->value;
        $professional_tax = Setting::find(4)->value;
         if(request()->ajax() || request()->wantsJson()){

            $sort= request()->has('sort')?request()->get('sort'):'year_salary';
            $order= request()->has('order')?request()->get('order'):'asc';
            
            $payment_history = Payment::where('employee_id',$id)->orderBy("$sort", "$order")->paginate(10);
           

            $paginator=[
                'total_count'  => $payment_history->total(),
                'total_pages'  => $payment_history->lastPage(),
                'current_page' => $payment_history->currentPage(),
                'limit'        => $payment_history->perPage()
            ];
            return response([
                "data"        =>$payment_history->all(),
                "paginator"   =>$paginator,
                "status_code" =>200
            ],200);
        }

       return view('employee.payment.history',compact('employee_id','esi','professional_tax'));   
    }

    public function showpayslip($id){

        $data = \DB::table('employee_payment')->select('employee_payment.*','employees.first_name','employees.last_name','employees.current_salary','employees.remaining_leaves','employees.pf','employees.loan_amount','employees.gross_salary','j.job_title as designation','d.name as department_name')
            ->join('employees','employees.id','=','employee_payment.employee_id')
            ->join('employee_job','employee_job.employee_id','=','employee_payment.employee_id')
            ->join('jobs as j','j.job_id','=','employee_job.job_job_id')
            ->join('departments as d','d.id','=','j.department_id')
            ->whereNull('employee_job.end_date')
            ->where('employee_payment.id',$id)->get();

        return response([
                "data"        =>$data,
                "status_code" =>200
            ],200);
    }

    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
