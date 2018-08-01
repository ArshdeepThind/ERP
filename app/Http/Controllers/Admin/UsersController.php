<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\WelcomMail;

use App\Employee;
use App\Traits\FileManipulationTrait;
use App\Transformers\UsersTransformer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Image;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use FileManipulationTrait;

    protected $employee;
    protected $userTransformer;

    public function __construct(Employee $employee,UsersTransformer $userTransformer)
    {
        $this->employee            = $employee;
        $this->userTransformer     = $userTransformer;
    }

    public function index()
    {
     
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
            ->leftJoin('employee_role', 'employee_role.employee_id', '=', 'employees.id')
            ->leftJoin('roles', 'roles.id', '=', 'employee_role.role_id')
            ->select('employees.*', 'roles.name as inrole')
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
        return view('admin.users.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('users.create')) {
            return back();
        }

        return view('admin.users.add',['defaultImg'=>$this->getFileUrl('user/avatar.png')]);
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
    
    public function fetchData($id)
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
        $user=$this->employee->find($id);
        if(!$user){
            flash(trans("messages.user-not-found"),'info');
            return back();
        }
        $validator = validator()->make($request->all(), [
            'inrole' => 'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }

        extract($request->all());

        // hasRole

        if(! $user->hasRole($inrole)){
            $existingRoles=$this->userTransformer->roleName($user->roles);
            foreach ($existingRoles as $eachRole) {
                //  Detaching the acces
                $user->detachRole($eachRole);
            }
            //  Attching a new access
            $user->assignRole($inrole);
        }

        if($request->wantsJson()){
            return response([
                "data"        =>[],
                "message"     =>trans('Assign Role'),
                "status_code" =>200
            ],200);
        }
        flash(trans('Assign Role'),'success');
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
    
    }

    public function destroyBulk(Request $request){
    }

    
}
