<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $job;

    public function __construct(Job $job)
    {
        $this->job            = $job;
    }

    public function fetchDepartments(){

            $departments= \DB::table('departments')->select('departments.*')->get()->toarray();

            return response([
                "data"        =>$departments,
                "status_code" =>200
            ],200);
                
    }
    
    public function index()
    {
        /*if (\Gate::denies('developerOnly') && \Gate::denies('users.list')) {
            return back();
        }*/
         if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'job_title';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            $job= \DB::table('jobs')
                ->where(function ($query) use($search){
                    $query->where('jobs.job_title','like',"$search%")
                          ->orWhere('d.name','like',"$search%");
                })
                ->where('jobs.is_deleted',0)
                ->join('departments as d', 'd.id', '=', 'jobs.department_id')
                ->select('jobs.*', 'd.name as department_name','d.id as department_id')
                ->orderBy("$sort", "$order")
                ->paginate(10);
            
            $paginator=[
                'total_count'  => $job->total(),
                'total_pages'  => $job->lastPage(),
                'current_page' => $job->currentPage(),
                'limit'        => $job->perPage()
            ];
            return response([
                "data"        =>$job->all(),
                "paginator"   =>$paginator,
                "status_code" =>200
            ],200);

            
        }
        return view('employee.job.list');
      
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
    public function store(Request $request)
    {
        /*if (\Gate::denies('developerOnly') && \Gate::denies('admin.create')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }
        */// VALIDATION OF INPUT
        $validator = validator()->make($request->all(), [
            'job_title'   =>'required|max:255|unique:jobs',
            'job_description'  => 'required',
            'department_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }
        # Prepare input
        
        $input             =    array_only($request->all(),["job_title","job_description","yearly_leaves","experience_required","no_of_vacancies","department_id"]);
        

        # Store
        $job = $this->job->create($input);
        
        # Respond in JSON
        if($request->wantsJson()){
            return response([
                "message"     =>trans('Job added successfully'),
                "status_code" =>201
            ],201);
        }
        flash(trans('Job added successfully'),'success');
        return back();
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
        /*if (\Gate::denies('developerOnly') && \Gate::denies('admin.update')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }*/
        
        $job=Job::findOrFail($id);
        $validator = validator()->make($request->all(), [
            'job_title'   =>'required|max:255',
            'job_description'  => 'required',
            'department_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        
        $job->job_title  =$job_title;
        $job->job_description =$job_description;
        $job->department_id =$department_id;
        $job->experience_required =$experience_required;
        $job->no_of_vacancies =$no_of_vacancies;
        $job->yearly_leaves =$yearly_leaves;
        $job->save();

        if($request->wantsJson()){
            return response([
                "message"     =>trans('Job updated successfully'),
                "status_code" =>200
            ],200);
        }
        flash(trans('Job updated successfully'),'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*if (\Gate::denies('developerOnly') && \Gate::denies('admin.delete')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }*/
        $job=$this->job->findOrFail($id);
        $job->is_deleted();
        return response([
            "data"        =>[],
            "message"     =>trans('Job deleted'),
            "status_code" =>200
        ],200);
    }
}
