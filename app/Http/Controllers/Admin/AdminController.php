<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Traits\FileManipulationTrait;
use App\Transformers\AdminTransformer;
//use Illuminate\Support\Facades\Mail;
use App\Mail\welcomeadmin;


class AdminController extends Controller
{
    use FileManipulationTrait;

    protected $admin;
    protected $adminTransformer;

    public function __construct(Admin $admin, AdminTransformer $adminTransformer)
    {
        $this->admin            = $admin;
        $this->adminTransformer = $adminTransformer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('admin.list')) {
            return back();
        }
        // If there is an Ajax request or any request wants json data
        if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            
            $admin= \DB::table('admins')
                ->where('admins.id','<>',auth()->user()->id)
                ->where('admins.id','<>',1)
                ->where(function ($query) use($search){
                    $query->where('admins.name','like',"$search%")
                          ->orWhere('admins.email','like',"$search%")
                          ->orWhere('roles.name','like',"$search%");
                })
                ->join('admin_role', 'admin_role.admin_id', '=', 'admins.id')
                ->join('roles', 'roles.id', '=', 'admin_role.role_id')
                ->select('admins.*', 'roles.name as role')
                ->orderBy("$sort", "$order")
                ->paginate(10);
            
            $paginator=[
                'total_count'  => $admin->total(),
                'total_pages'  => $admin->lastPage(),
                'current_page' => $admin->currentPage(),
                'limit'        => $admin->perPage()
            ];
            return response([
                "data"        =>    $this->adminTransformer->transformCollection($admin->all()),
                "paginator"   =>    $paginator,
                "status_code" =>    200
            ],200);
        }
        return view('admin.administrator.list');
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('admin.create')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }
        // VALIDATION OF INPUT
        $validator = validator()->make($request->all(), [
            'name'   =>'required|max:255',
            'email'  => 'required|email|max:255|unique:admins',
            'inrole' => 'required'
        ]);
        
        if ($validator->fails()) {

            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }
        # Prepare input
        $setPassword       =    randomInteger();
        $input             =    array_only($request->all(),["name","email"]);
        $input['password'] =    bcrypt($setPassword);

        # Store
        $admin = $this->admin->create($input);
        if($request->get('inrole')!=""){
            $admin->assignRole($request->get('inrole'));
        }
        
        # Sending Mail
        /*$mail=new WelcomeNewAdmin($admin,$setPassword);
        \Mail::to($admin->email)->send($mail);*/

  //      Mail::to($admin->email)->send(new welcomeadmin($admin,$setPassword));

        $newAdmin          =$admin->toArray();
        $newAdmin['roles'] =$admin->roles;

        # Respond in JSON
        if($request->wantsJson()){
            return response([
                "data"        =>$this->adminTransformer->single($newAdmin),
                "message"     =>trans('messages.admin-add'),
                "status_code" =>201
            ],201);
        }
        flash(trans('messages.admin-add'),'success');
        return back();
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
        if (\Gate::denies('developerOnly') && \Gate::denies('admin.update')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }
        $admin=Admin::findOrFail($id);
        $validator = validator()->make($request->all(), [
            'name'   =>'required|max:255',
            'email'  => 'required|email|max:255|unique:admins,email,'.$admin->id,
            'inrole' => 'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        // Update in Role
        if(! $admin->hasRole($inrole)){
            $existingRoles=$this->adminTransformer->roleName($admin->roles);
            foreach ($existingRoles as $eachRole) {
                //  Detaching the acces
                $admin->detachRole($eachRole);
            }
            //  Attching a new access
            $admin->assignRole($inrole);
        }

        // SET AND UPDATE
        $admin->name  =$name;
        $admin->email =$email;
        $admin->save();

        if($request->wantsJson()){
            return response([
                "data"        =>$this->adminTransformer->single($admin->toArray()),
                "message"     =>trans('messages.admin-update'),
                "status_code" =>200
            ],200);
        }
        flash(trans('messages.admin-add'),'success');
        return back();
    }

    public function switchStatus(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('admin.update')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }
        $validator = validator()->make($request->all(), [
            'id'   =>'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        $admin= $this->admin->findOrFail($id);
        if($admin->count() > 0){
            $newStatus = ($admin->status=='active')?'inactive':'active';
            $admin->status=$newStatus;
            $admin->save();
            // Get New updated Object of Admin
            $updated = $admin->toArray();
            $updated['roles'] =$admin->roles;

            if($request->wantsJson()){
                return response([
                    "data"        =>$this->adminTransformer->single($updated),
                    "message"     =>trans('messages.admin-status',["status"=>$newStatus]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.admin-status',["status"=>$newStatus]),'success');
            return back();
        }
        flash(trans('messages.admin-update-fail'),'error');
        return back();
    }

    public function switchStatusBulk(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('user.update')) {
            return back();
        }
        $input= $request->all();
        if (count($input)==0) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        $admins= $this->admin->whereIn('id',$request->all())->get();
        if($admins->count() > 0){
            foreach ($admins as $admin) {
                $newStatus = ($admin->status=='active')?'inactive':'active';
                $admin->status=$newStatus;
                $admin->save();
            }

            if($request->wantsJson()){
                return response([
                    "data"=>[],
                    // "data"        =>$this->adminTransformer->transform($updated),
                    "message"     =>trans('messages.admin-status',["status"=>"updated"]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.admin-status',["status"=>"updated"]),'success');
            return back();
        }
        flash(trans('messages.admin-update-fail'),'error');
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
        if (\Gate::denies('developerOnly') && \Gate::denies('admin.delete')) {
            return response(["error"=>trans('messages.unauthorized-access')],401);
        }
        $admin=$this->admin->findOrFail($id);
        $admin->delete();
        return response([
            "data"        =>[],
            "message"     =>trans('messages.admin-distroy'),
            "status_code" =>200
        ],200);
    }
    public function destroyBulk(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('admin.delete')) {
            return back();
        }
        $this->admin->destroy($request->all());
        return response([
            "data"=>[],
            "message"=>trans('messages.admin-distroy'),
            "status_code"=>200
        ],200);
    }

    public function profileEdit(){
        /*if (\Gate::denies('edit_profile')) {
            return view('errors.503');
        }*/

        if(auth()->user()->pic!='')
            $adminPic = $this->getFileUrl(auth()->user()->pic);
        else
            $adminPic = $this->getFileUrl('avatar.png');

        return view('admin.profile', compact('adminPic'));
    }

    public function profileUpdate(Request $request,Admin $admin){

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
            $path = $this->uploadAs($pic,'admin/'.$admin->id);
            $admin->pic=$path;
        }
        //  Save the name of admin
        $admin->name=$request->get('name');
        $admin->save();
        //  Throw the flash message


        flash(trans('messages.admin-profile-update'),'success');
        return back()->with('mode',$mode);
    }

    /**
     * [changePassword description]
     * @param  Request $request [description]
     * @param  Admin   $admin   [description]
     * @return [type]           [description]
     */
    public function changePassword(Request $request, Admin $admin){

        $mode = $request->has('mode')?$request->get('mode'):'pf';

        if (! \Hash::check($request->get('oldpassword'), $admin->password))
        {
            flash(trans("passwords.oldpassword-wrong"),'danger');
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
        $admin->password=bcrypt($request->get('password'));
        $admin->save();
        //  Throw the flash message
        flash(trans('passwords.reset'),'success');
        return back()->with('mode',$mode);
    }
}