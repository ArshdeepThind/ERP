<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Employee;
use App\Setting;

class ScheduleSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScheduleSalary:salarycalculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Salary Calculation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function countDays($year, $month, $ignore) {
        $count = 0;
        $counter = mktime(0, 0, 0, $month, 1, $year);
        while (date("n", $counter) == $month) {
            if (in_array(date("w", $counter), $ignore) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        return $count;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $month = date('m', strtotime(date('Y-m')." -1 month"));
        $year = date('Y', strtotime(date('Y-m')." -1 month"));

        $total_working_days = $this->countDays($year, $month, array(0, 6));

        $employees = Employee::with('jobs')->get();

        foreach ($employees as $employee) {
            $total_days_worked_employee = DB::table('employee_attendance')->where('employee_id',$employee->id)->whereMonth('date',$month)->groupBy('employee_id')->count();

            $total_leaves = DB::table('employee_leave')->where('employee_id',$employee->id)->whereMonth('start_date',$month)->whereMonth('end_date',$month)->where('is_approved',1)->groupBy('employee_id')->sum('deducted_leaves');

            $total_non_paid_leaves = DB::table('employee_leave')->where('employee_id',$employee->id)->whereMonth('start_date',$month)->whereMonth('end_date',$month)->where('is_approved',1)->groupBy('employee_id')->sum('non_paid_leaves');

            $esi = Setting::find(3)->value;
            $professional_tax = Setting::find(4)->value;

            if($total_non_paid_leaves>0){
                $total_days_to_pay = ($total_working_days - $total_non_paid_leaves);
                $base_salary_month = $total_days_to_pay * ($employee->current_salary * 8);
                $gross_salary_month = $total_days_to_pay * ($employee->gross_salary * 8);
                $total_salary_sum = $base_salary_month + $gross_salary_month;
                $total_salary_deducted = $esi + $professional_tax + $employee->loan_amount;
                $net_salary = $total_salary_sum - $total_salary_deducted;

                DB::table('employee_payment')->insert(array('employee_id' => $employee->id,'net_salary' => $net_salary,'base_salary_month' => $base_salary_month,'gross_salary_month' => $gross_salary_month,'total_salary_deducted'=>$total_salary_deducted,'total_days_worked' => $total_days_to_pay,'total_leaves'=>$total_leaves,'total_non_paid_leaves' => $total_non_paid_leaves,'paying_date' => date('Y-m-d'),'is_paid' => 'process','month_salary'=>$month,'year_salary'=>$year,'created_at' => date('Y-m-d H:i:s')));

            }else{
                $base_salary_month = $total_working_days * ($employee->current_salary * 8);
                $gross_salary_month = $total_working_days * ($employee->gross_salary * 8);
                $total_salary_sum = $base_salary_month + $gross_salary_month;
                $total_salary_deducted = $esi + $professional_tax + $employee->loan_amount;
                $net_salary = $total_salary_sum - $total_salary_deducted;

                DB::table('employee_payment')->insert(array('employee_id' => $employee->id,'net_salary' => $net_salary,'base_salary_month' => $base_salary_month,'gross_salary_month' => $gross_salary_month,'total_salary_deducted'=>$total_salary_deducted,'total_days_worked' => $total_working_days,'total_leaves'=>$total_leaves,'total_non_paid_leaves' => $total_non_paid_leaves,'paying_date' => date('Y-m-d'),'is_paid' => 'process','month_salary'=>$month,'year_salary'=>$year,'created_at' => date('Y-m-d H:i:s')));

            }

        }
        //DB::table('employees')->
    }
}
