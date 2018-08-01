<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Employee;
use App\Products;
use App\Supplier;
use App\Job;


class DashController extends Controller
{

    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {   
        $employeecount = Employee::whereNull('deleted_at')->count();
        $jobscount = Job::where('is_deleted',0)->count();
        $departmentcount = \DB::table('departments')->count();

        $latestmember = \DB::table('employees')->select('employees.*')->where('joining_date','>',Carbon::now()->subMonth(2))->get();

        $latestmembercount = \DB::table('employees')->select('employees.*')->where('joining_date','>',Carbon::now()->subMonth(2))->count();

        $opening = \DB::table('jobs')->select('jobs.job_title','jobs.no_of_vacancies','d.name')->join('departments as d','d.id','=','jobs.department_id')->orderBy("jobs.no_of_vacancies","desc")->limit(3)->get();

        $from = Carbon::now()->startofMonth();
        $to = Carbon::now()->startofMonth()->addMonth(3);
        $birthdays = \DB::table('employees')->select('employees.*')->whereMonth('date_of_birth','>=',$from->month)->whereMonth('date_of_birth','<=',$to->month)->get();
       // mprd($birthdays);

        $sql = "DATEDIFF('".date('Y-m-d')."', joining_date) >= 365";

        $work_anniversary = \DB::table('employees')->select('employees.*')->whereRaw($sql)->get();


        //Inventory

        $productscount = Products::count();
        $suppliercount = Supplier::count();
        
        $maxproduct = Products::select('product_category','product_name')
                    ->where('quantity_shipped','>=','85')
                    ->get();
               
        $a = [$productscount,$suppliercount,$maxproduct];

        return view('employee.home',compact('employeecount','jobscount','departmentcount','latestmember','latestmembercount','opening','birthdays','work_anniversary','a'));
    }

}
