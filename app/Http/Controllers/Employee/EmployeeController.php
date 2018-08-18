<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Mail\WelcomMail;
use App\Employee;
use App\Job;
use App\EmployeeLeave;
use App\Traits\FileManipulationTrait;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Image;

class EmployeeController extends Controller
{
    use FileManipulationTrait;

    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee            = $employee;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*if (\Gate::denies('developerOnly') && \Gate::denies('users.list')) {
            return back();
        }*/
        // If there is an Ajax request or any request wants json data
        if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'first_name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            $employees = $this->employee->where(function($query) use ($search)
            {
                if ($search) {
                    $query->where('first_name','like',"$search%")
                        ->orWhere('last_name','like',"$search%")
                        ->orWhere('email','like',"$search%");
                }
            })
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
        return view('employee.employee.list');
      
    }

    public function showattendence(){

        if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'first_name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            
            $employee= \DB::table('employees')
                ->where(function ($query) use($search){
                    $query->where('employees.first_name','like',"$search%")
                          ->orWhere('employees.email','like',"$search%");
                })
                ->select('employees.*')
                ->orderBy("$sort", "$order")
                ->paginate(10);
            
            $paginator=[
                'total_count'  => $employee->total(),
                'total_pages'  => $employee->lastPage(),
                'current_page' => $employee->currentPage(),
                'limit'        => $employee->perPage()
            ];
            return response([
                "data"        =>    $employee->all(),
                "paginator"   =>    $paginator,
                "status_code" =>    200
            ],200);
        }
        return view('employee.employee.attendence');
    }

    public function showallleaves($id){

        $leaves = \DB::table('employee_leave')->join('leaves as l','l.leave_id','=','employee_leave.leave_id')->select('employee_leave.*','l.leave_type as leave_type')->where('employee_leave.employee_id',$id)->where('is_approved',1)->get()->toarray();

        return response([
                "data"        =>$leaves,
                "status_code" =>200
            ],200);
    }

    public function allleaves(){

            $leaves= \DB::table('leaves')->select('leaves.*')->get()->toarray();

            return response([
                "data"        =>$leaves,
                "status_code" =>200
            ],200);
                
    }

    public function leaves()
    {
        /*if (\Gate::denies('developerOnly') && \Gate::denies('users.list')) {
            return back();
        }*/
        // If there is an Ajax request or any request wants json data
        if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'first_name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            $employees = $this->employee->with('jobs')->where(function($query) use ($search)
            {
                if ($search) {
                    $query->where('first_name','like',"$search%")
                        ->orWhere('last_name','like',"$search%")
                        ->orWhere('email','like',"$search%");
                }
            })
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
        return view('employee.employee.leaves');
      
    }

    public function unapprovedleaves()
    {
        /*if (\Gate::denies('developerOnly') && \Gate::denies('users.list')) {
            return back();
        }*/
        // If there is an Ajax request or any request wants json data
        if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'first_name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            
            $employee_leaves = \DB::table('employee_leave')->where(function ($query) use($search){
                    $query->where('e.first_name','like',"$search%")
                          ->orWhere('e.last_name','like',"$search%")
                          ->orWhere('e.email','like',"$search%")
                          ->orWhere('l.leave_type','like',"$search%");
                })->join('leaves as l','l.leave_id','=','employee_leave.leave_id')
                ->join('employees as e','e.id','=','employee_leave.employee_id')
                ->select('employee_leave.*','l.leave_type as leave_type','e.*','employee_leave.created_at as applied_on','employee_leave.id as employee_leave_id')
                ->where('is_approved',0)
                ->orderBy("$sort", "$order")
                ->paginate(10);


            $paginator=[
                'total_count'  => $employee_leaves->total(),
                'total_pages'  => $employee_leaves->lastPage(),
                'current_page' => $employee_leaves->currentPage(),
                'limit'        => $employee_leaves->perPage()
            ];
            return response([
                "data"        =>$employee_leaves->all(),
                "paginator"   =>$paginator,
                "status_code" =>200
            ],200);

            
        }
        return view('employee.employee.unapprovedleaves');
      
    }

    public function applyleave(Request $request){

        $validator = validator()->make($request->all(), [
            'leave_id'   =>'required',
            'reason'  => 'required',
            'deducted_leaves' => 'required'
        ]);
        
        if ($validator->fails()) {
            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }

        $input             =    array_only($request->all(),["leave_id","employee_id","deducted_leaves","reason","start_date","end_date"]);
        $input['is_approved'] = 1;

        $employee = Employee::find($input['employee_id']);

        $remaining_leaves = $employee->remaining_leaves;

        if($remaining_leaves<$input['deducted_leaves']){
            $final_leaves =  $input['deducted_leaves']-$remaining_leaves;
            $employee->remaining_leaves = 0;    
            $input['non_paid_leaves'] = $final_leaves;
        }else{
            $final_leaves =  $remaining_leaves - $input['deducted_leaves'];
            $employee->remaining_leaves = $final_leaves;    
            $input['non_paid_leaves'] = 0;
        }
        
        $employee->save();

        # Store
        $leave = EmployeeLeave::create($input);
        
        # Respond in JSON
        if($request->wantsJson()){
            return response([
                "message"     =>trans('Leave added'),
                "status_code" =>201
            ],201);
        }
        flash(trans('Leave added successfully'),'success');
        return back();
    }

    public function create()
    {
        /*
        if (\Gate::denies('developerOnly') && \Gate::denies('users.create')) {
            return back();
        }*/

        $jobs = Job::where('is_deleted',0)->get();

        $defaultImg = $this->getFileUrl('employee/avatar.png');
        return view('employee.employee.add',compact('jobs','defaultImg'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($hasPicture=$request->has('pic') && $request->get('ispicchange') == true){
            $this->validate($request, [
                'first_name' =>'required|max:255|alpha',
                'password'  => 'required',
                'last_name' => 'required|alpha',
                'job'   => 'required',
                'joining_date' => 'required',
                'email' =>'required|email|unique:employees,email,NULL,id,deleted_at,NULL',
                'phone_number' =>'required',
                'pic'  =>'required|image64:jpg,jpeg,bmp,png',
                'gender' => 'required',
                'marital_status' => 'required',
                'address1' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required'
            ]);
        }else{
            $this->validate($request, [
                'first_name' =>'required|max:255|alpha',
                'password'  => 'required',
                'last_name' => 'required|alpha',
                'job'   => 'required',
                'joining_date' => 'required',
                'email' =>'required|email|unique:employees,email,NULL,id,deleted_at,NULL',
                'phone_number' =>'required',
                'gender' => 'required',
                'marital_status' => 'required',
                'address1' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required'
            ]);
        }

         $setPassword        = $request->get('password');
         $emailtemppass      = $setPassword;

        $input              = array_only($request->all(),["first_name","last_name","email",'phone_number','gender','joining_date','marital_status','address1','address2','zipcode','city','state','current_salary','gross_salary','loan_amount','country']);
        $input['date_of_birth'] = $request->get('dob');
        $input['password']  = bcrypt($setPassword);
        
        

        $job_id = $request->get('job');

        $jobobj = Job::find($job_id);
        $input['remaining_leaves'] = $jobobj->yearly_leaves;
        
        $employee = $this->employee->create($input);

        $start_date = date('Y-m-d');
        $employee->jobs()->attach($job_id,['start_date' => $start_date]);

        if($request->has('pic') && $request->get('ispicchange') == true){

            $imageData = $request->get('pic');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            $this->createDir('public/employee/'.$employee->id);
            Image::make($request->get('pic'))->save(storage_path('app/public/employee/'.$employee->id.'/').$fileName);
            $employee->pic=$fileName;
            $employee->save();
        }

        Mail::to($request->get('email'))->send(new WelcomMail($employee,$emailtemppass));

        $senderID = auth()->user()->id;
        $receiverID = $employee->id;

        $conversation_id = str_random(22);

        $conversation = \DB::table('conversation')->insert(['senderID' => $senderID,'receiverID' => $receiverID, 'conversation_id' => $conversation_id]);

        return response([
            "receiverName"   => $employee->first_name,
            "receiverID" => $employee->id,
            "senderID" => auth()->user()->id,
            "senderName" => auth()->user()->first_name,
            'message' => $conversation_id
        ]);
    }

    public function cases(Request $request, $id)
    {
         $name = auth()->user()->first_name;
         $employee = Employee::select('employees.first_name as receiverName', 'c.receiverID as receiverID','c.senderID as senderID')
                ->where('c.conversation_id', $id)
                ->where('c.senderID', auth()->user()->id)
                ->leftJoin('conversation as c', 'c.receiverID', '=', 'employees.id')->first()->toarray();

        return response([
            "receiverName"   => $employee['receiverName'],
            "receiverID" => $employee['receiverID'],
            "senderID" => $employee['senderID'],
            "senderName" => $name,
            "status_code" => 200
        ], 200);
    }


    
    public function edit($id)
    {

        $employee=$this->employee->with('jobs')->find($id);
        
        $jobs = Job::where('is_deleted',0)->get();

        if(!$employee){
            flash(trans("employee not found"),'info');
            return back();
        }



        $picture=($employee->pic=='avatar.png')?$this->getFileUrl('employee/'.$employee->pic):$this->getFileUrl('employee/'.$id.'/'.$employee->pic);

        $employeeId = $id;
       // echo $picture; exit;
        return view('employee.employee.edit',compact('employee','picture','employeeId','jobs'));
    }

    public function fetchData($id)
    {
        $employee=$this->employee->with('jobs')->find($id);
    

        if(!$employee){
            flash(trans("employee not found"),'info');
            return back();
        }

        return response([
            "data"        => $employee,
            "status_code" =>200
        ],200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $employee=$this->employee->with('jobs')->find($id);

        if(!$employee){
            flash(trans("Employee not found"),'info');
            return back();
        }
        
        // VALIDATION OF INPUT
        if($hasPicture=$request->has('pic') && $request->get('ispicchange') == true){
            $this->validate($request, [
                'first_name' =>'required|max:255|alpha',
                'last_name' => 'required|alpha',
                'job'   => 'required',
                'joining_date' => 'required',
                'email' =>'required|email',
                'phone_number' =>'required',
                'pic'  =>'required|image64:jpg,jpeg,bmp,png',
                'gender' => 'required',
                'marital_status' => 'required',
                'address1' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required'
            ]);
        }
        else{
            $this->validate($request, [
                'first_name' =>'required|max:255|alpha',
                'last_name' => 'required|alpha',
                'job'   => 'required',
                'joining_date' => 'required',
                'email' =>'required|email',
                'phone_number' =>'required',
                'gender' => 'required',
                'marital_status' => 'required',
                'address1' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required'
            ]);
        }
        # Prepare input
        $input = array_only($request->all(),["first_name","last_name","email",'phone_number','gender','joining_date','current_salary','marital_status','address1','address2','zipcode','city','state','country','gross_salary','loan_amount']);
        $input['date_of_birth'] = $request->get('dob');
        extract($input);

        // hasRole
        $job_id = $request->get('job');

        if($employee->jobs->toarray()[0]['job_id'] != $job_id){

            $employee->jobs()->updateExistingPivot($employee->jobs->toarray()[0]['job_id'], array('end_date' => date('Y-m-d')));

            $start_date = date('Y-m-d');
            $employee->jobs()->attach($job_id,['start_date' => $start_date]);
        }

        if($request->has('pic')){

            if($request->get('ispicchange'))
            {
                $imageData = $request->get('pic');
                $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                $this->createDir('public/employee/'.$employee->id);
                Image::make($request->get('pic'))->save(storage_path('app/public/employee/'.$employee->id.'/').$fileName);

                $this->destoryFile('employee/'.$employee->id.'/'.$employee->pic);
                
                $employee->pic=$fileName;
            }
        }
        // If has pic then update new pic
       
        $employee->first_name=$first_name;
        $employee->last_name=$last_name;
        $employee->email=$email;
        $employee->phone_number=$phone_number;
        $employee->gender=$gender;
        $employee->marital_status=$marital_status;
        $employee->date_of_birth=$date_of_birth;
        $employee->joining_date=$joining_date;
        $employee->address1=$address1;
        $employee->address2=$address2;
        $employee->zipcode=$zipcode;
        $employee->city=$city;
        $employee->state=$state;
        $employee->country=$country;
        $employee->current_salary=$current_salary;
        $employee->gross_salary=$gross_salary;
        $employee->loan_amount=$loan_amount;

        if(!empty($request->get('password'))){
            $employee->password = bcrypt($request->get('password'));           
        }
        $employee->save();
        
        # Respond in JSON
        return response(['message' => trans('Employee Updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee=$this->employee->with('jobs')->find($id);
        
        if($employee->pic!='avatar.png')
            $this->destoryFile('employee/'.$employee->id.'/'.$employee->pic);


        $employee->deleted_at = date('Y-m-d H:i:s');
        $employee->save();
        return response([
            "data"=>[],
            "message"=>trans('Employee Updated'),
            "status_code"=>200
        ],200);

    }

    public function profileEdit(){
        /*if (\Gate::denies('edit_profile')) {
            return view('errors.503');
        }*/

        if(auth()->user()->pic!='')
            $adminPic = $this->getFileUrl('employee/'.auth()->user()->id.'/'.auth()->user()->pic);
        else
            $adminPic = $this->getFileUrl('avatar.png');

        return view('employee.profile', compact('adminPic'));
    }

    public function profileUpdate(Request $request,Employee $employee){

        $mode = $request->has('mode')?$request->get('mode'):'pf';
        # Validation CASE-1 : has file then it must be image with name is required
        if($hasPicture=$request->hasFile('pic')){
            $validator = validator()->make($request->all(), [
                'name' =>'required|max:255',
                'pic'  =>'image|mimes:jpeg,bmp,png'
            ]);    
        }
        # Validation CASE-1 : there is no-file in request
        else{
            $validator = validator()->make($request->all(), ['name'=>'required|max:255']);
        }
        # If validation fail
        if ($validator->fails()) {

            flash(trans("messages.parameters-fail-validation"),'danger');
            return back()->withErrors($validator)->withInput($request->all());
        }

        // If has pic then upload new pic
        if($request->hasFile('pic')){
            $pic  = $request->file('pic');
            $path = $this->uploadAs($pic,'employee/'.$employee->id);
            $employee->pic=$path;
        }
        //  Save the name of admin
        $employee->first_name=$request->get('name');
        $employee->save();
        //  Throw the flash message


        flash(trans('Profile updated successfully'),'success');
        return back()->with('mode',$mode);
    }

    /**
     * [changePassword description]
     * @param  Request $request [description]
     * @param  Admin   $admin   [description]
     * @return [type]           [description]
     */
    public function changePassword(Request $request, Employee $employee){

        $mode = $request->has('mode')?$request->get('mode'):'pf';

        if (! \Hash::check($request->get('oldpassword'), $employee->password))
        {
            flash(trans("Oldpassword wrong"),'danger');
            return back()->withInput();
        }
        # Validation CASE-1 : has file then it must be image with name is required
        $validator = validator()->make($request->all(), [
            'oldpassword'           =>'required',
            'password'              =>'required|min:6|max:16',
            'password_confirmation' =>'required|same:password',
        ]);    
        
        # If validation fail
        if ($validator->fails()) {
            flash(trans("messages.parameters-fail-validation"),'danger');
            return back()->withErrors($validator)->withInput($request->all());
        }
        //  Save the New Password
        $employee->password=bcrypt($request->get('password'));
        $employee->save();
        //  Throw the flash message
        flash(trans('Reset Password Successfully'),'success');
        return back()->with('mode',$mode);
    }

    public function switchStatus(Request $request){
        
        $validator = validator()->make($request->all(), [
            'employee_leave_id'   =>'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        $employee_leaves= EmployeeLeave::findOrFail($employee_leave_id);
        if($employee_leaves->count() > 0){

            $employee = Employee::find($employee_leaves->employee_id);

            $remaining_leaves = $employee->remaining_leaves;

            if($remaining_leaves<$employee_leaves->deducted_leaves){
                $final_leaves =  $employee_leaves->deducted_leaves-$remaining_leaves;
                $employee->remaining_leaves = 0;    
                $employee_leaves->non_paid_leaves = $final_leaves;
            }else{
                $final_leaves =  $remaining_leaves - $employee_leaves->deducted_leaves;
                $employee->remaining_leaves = $final_leaves;    
                $employee_leaves->non_paid_leaves = 0;
            }
        
            $employee->save();

            $employee_leaves->is_approved = 1;
            $employee_leaves->save();
            // Get New updated Object of Admin
           
            if($request->wantsJson()){
                return response([
                    "message"     =>trans('leave approved'),
                    "status_code" =>200
                ],200);
            }
            flash(trans('leave approved'),'success');
            return back();
        }
        
        return back();
    }

    public function updateBulk(Request $request){
        $input= $request->all();
        if (count($input)==0) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        $employees= $this->employee->whereIn('id',$request->all())->get();
        if($employees->count() > 0){
            foreach ($employees as $employee) {
                /*$date = date('d.m.Y');
                $is_attend->'yes';*/
                $id = \DB::table('employee_attendance')->insert(
                    ['date' => date('Y-m-d'), 'employee_id' => $employee->id , 'is_attend' => 1]);
                // $employee->save();
            }

            if($request->wantsJson()){
                return response([
                    "data"=>[],
                    // "data"        =>$this->employeeTransformer->transform($updated),
                    "message"     =>trans('messages.employee-status',["status"=>"updated"]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.employee-status',["status"=>"updated"]),'success');
            return back();
        }
        flash(trans('messages.employee-update-fail'),'error');
        return back();
    }

    public function storeRemarks(Request $request)
    {
        
        $validator = validator()->make($request->all(), [
            'remarks'   =>'required|max:255'
        ]);
        // $validator1 = validator()->make($request1->all(), ['id' => 'required']);
        if ($validator->fails()) {

            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }
        # Prepare input
        
        $input             =    array_only($request->all(),["remarks","ids"]);
        //mprd($input);
        # Store

        if(!empty($input['ids'])){
            for($i=0;$i<count($input['ids']);$i++){
                $date = date('Y-m-d');

                \DB::table('employee_attendance')->where('employee_id',$input['ids'][$i])->where('date',$date)->update(['remarks' => $input['remarks']]);
            }

            if($request->wantsJson()){
                return response([
                    "data"=>[],
                    // "data"        =>$this->employeeTransformer->transform($updated),
                    "message"     =>trans("Remarks Added"),
                    "status_code" =>200
                ],200);
            }
            flash(trans('Remarks Added'),'success');
            return back();
        }
        flash(trans('Remarks Added'),'success');
        return back();
    }



}