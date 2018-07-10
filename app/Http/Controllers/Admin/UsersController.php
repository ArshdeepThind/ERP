<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\WelcomMail;

use App\User;
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

    protected $user;
    protected $userTransformer;

    public function __construct(User $user, UsersTransformer $userTransformer)
    {
        $this->user            = $user;
        $this->userTransformer = $userTransformer;
    }

    public function index()
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('users.list')) {
            return back();
        }
        // If there is an Ajax request or any request wants json data
        if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'firstname';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            $users=$this->user->whereHas('roles',function($query) use ($search)
            {
                if ($search) {
                    $query->where('firstname','like',"$search%")
                        ->orWhere('lastname','like',"$search%")
                        ->orWhere('email','like',"$search%");
                }

                $query->where('name','User');
            })
            ->orderBy("$sort", "$order")->paginate(10);
            $paginator=[
                'total_count'  => $users->total(),
                'total_pages'  => $users->lastPage(),
                'current_page' => $users->currentPage(),
                'limit'        => $users->perPage()
            ];
            return response([
                "data"        =>$this->userTransformer->transformCollection($users->all()),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('users.create')) {
            return back();
        }
        if($hasPicture=$request->has('pic') && $request->get('ispicchange') == true){
            $this->validate($request, [
                'firstname' =>'required|max:255|alpha',
                'lastname' => 'required|alpha',
                'email' =>'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                'phone' =>'required',
                'pic'  =>'required|image64:jpg,jpeg,bmp,png',
                'gender' => 'required'
            ]);
        }else{
            $this->validate($request, [
                'firstname' =>'required|max:255|alpha',
                'lastname' => 'required|alpha',
                'email' =>'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                'phone' =>'required',
                'gender' => 'required'
            ]);
        }

        $setPassword        = randomInteger();
        $emailtemppass      = $setPassword;
        $input              = array_only($request->all(),["firstname","lastname","email",'phone','gender','dob']);
        $input['phone']     = trim($input['phone']); 
        $input['password']  = md5($setPassword);
        $input['wallet']    = 0;
        $input['credit']    = 0; 
        $input['is_reset']  = 0; 
        $input['api_token'] = str_random(60);

        # Store
        $user = $this->user->create($input);
        $user->assignRole('User');
       
          // If has pic then upload new pic
        if($request->has('pic') && $request->get('ispicchange') == true){

            $imageData = $request->get('pic');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            $this->createDir('public/user/'.$user->id);
            Image::make($request->get('pic'))->save(storage_path('app/public/user/'.$user->id.'/').$fileName);
            $user->pic=$fileName;
            $user->save();
        }

        Mail::to($request->get('email'))->send(new WelcomMail($user,$emailtemppass));

        return response(['message' => trans('messages.user-add')]);

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
        if (\Gate::denies('developerOnly') && \Gate::denies('users.edit')) {
            return back();
        }
        $user=$this->user->find($id);
        if(!$user){
            flash(trans("messages.user-not-found"),'info');
            return back();
        }



        $picture=($user->pic=='avatar.png')?$this->getFileUrl('user/'.$user->pic):$this->getFileUrl('user/'.$id.'/'.$user->pic);

        $userId = $id;
       // echo $picture; exit;
        return view('admin.users.edit',compact('user','picture','userId'));
    }

    public function fetchData($id)
    {
        
        $user=$this->user->find($id);
        if(!$user){
            flash(trans("messages.user-not-found"),'info');
            return back();
        }

        return response([
            "data"        => $user,
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
    public function update(Request $request, $id)
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('users.update')) {
            return back();
        }
        $user=$this->user->find($id);
        if(!$user){
            flash(trans("messages.user-not-found"),'info');
            return back();
        }
        
        // VALIDATION OF INPUT
        if($hasPicture=$request->has('pic') && $request->get('ispicchange') == true){
            $this->validate($request, [
                'firstname'    =>'required|max:255|alpha',
                'lastname' => 'required|alpha',
                'email'    => 'required|email|max:255|unique:users,email,'.$user->id.',id,deleted_at,NULL',
                'phone'    => 'required',
                'pic'      =>'required|image64:jpg,jpeg,bmp,png',
                'gender' => 'required'
            ]);
        }
        else{
            $this->validate($request, [
                'firstname'    =>'required|max:255|alpha',
                'lastname' => 'required|alpha',
                'email'  => 'required|email|max:255|unique:users,email,'.$user->id.',id,deleted_at,NULL',
                'phone'    => 'required',
                'gender' => 'required'
            ]);
        }
        # Prepare input
        $input = array_only($request->all(),["firstname","lastname",'email','phone','gender','dob']);
        
        extract($input);


        $input['phone']     = trim($phone); 

        // hasRole
        if(! $user->hasRole('User')){
            if(count($fetchUserRoles) > 0){
                foreach ($fetchUserRoles as $inrole) {
                    $user->detachRole($inrole);
                }
            }
            $user->assignRole('User');
        }


        if($request->has('pic')){

            if($request->get('ispicchange'))
            {
                $imageData = $request->get('pic');
                $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

                Image::make($request->get('pic'))->save(storage_path('app/public/user/'.$user->id.'/').$fileName);

                $this->destoryFile('user/'.$user->id.'/'.$user->pic);
                
                $user->pic=$fileName;

            }
            
            
        }
        // If has pic then update new pic
       
        $user->firstname=$firstname;
        $user->lastname=$lastname;
        $user->email=$email;
        $user->phone=$phone;
        $user->gender=$gender;
        $user->dob=$dob;
        $user->save();
        
        # Respond in JSON
        return response(['message' => trans('messages.user-update')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('users.delete')) {
            return back();
        }
        $user=$this->user->find($id);
        
        if($user->pic!='avatar.png')
            $this->destoryFile('user/'.$user->id.'/'.$user->pic);

        $fetchUserRoles=[];
        foreach ($user->roles as $role) {
            array_push($fetchUserRoles,$role->name);
        }

        if(count($fetchUserRoles) > 0){
            foreach ($fetchUserRoles as $inrole) {
                $user->detachRole($inrole);
            }
        }

        $user->delete();
        return response([
            "data"=>[],
            "message"=>trans('messages.user-distroy'),
            "status_code"=>200
        ],200);
    }

    public function destroyBulk(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('users.delete')) {
            return back();
        }

        $deleteArr = $request->all();
        for($i=0;$i<count($deleteArr);$i++)
        {
                $user=$this->user->find($deleteArr[$i]);
        
                if($user->pic!='avatar.png')
                    $this->destoryFile('user/'.$user->id.'/'.$user->pic);

                $fetchUserRoles=[];
                foreach ($user->roles as $role) {
                    array_push($fetchUserRoles,$role->name);
                }

                if(count($fetchUserRoles) > 0){
                    foreach ($fetchUserRoles as $inrole) {
                        $user->detachRole($inrole);
                    }
                }

                $user->delete();
        }

        return response([
            "data"=>[],
            "message"=>trans('messages.user-distroy'),
            "status_code"=>200
        ],200);
    }

    public function switchStatus(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('users.update')) {
            return back();
        }
        $validator = validator()->make($request->all(), [
            'id'   =>'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        $user= $this->user->find($id);
        if($user){
            $newStatus = ($user->status==1)?0:1;
            $user->status=$newStatus;
            $user->save();
            // Get New updated Object of User
            $updated = $user;

            if($request->wantsJson()){
                return response([
                    "data"        =>$this->userTransformer->transform($updated),
                    "message"     =>trans('messages.user-status',["status"=>$newStatus]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.user-status'),'success');
            return back();
        }
        flash(trans('messages.user-update-fail'),'error');
        return back();
    }

    public function switchStatusBulk(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('users.update')) {
            return back();
        }
        $input= $request->all();
        if (count($input)==0) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        $users= $this->user->whereIn('id',$request->all())->get();
        if($users->count() > 0){
            foreach ($users as $user) {
                $newStatus = ($user->status==1)?0:1;
                $user->status=$newStatus;
                $user->save();
            }

            if($request->wantsJson()){
                return response([
                    "data"=>[],
                    "message"     =>trans('messages.user-status',["status"=>"updated"]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.user-status'),'success');
            return back();
        }
        flash(trans('messages.user-update-fail'),'error');
        return back();
    }

    public function changePassword($id){

        $user = $this->user->find($id);
        $currenttime = time();
        $comparetime = strtotime($user->reset_expiry_date);

        if($currenttime<$comparetime && $user->is_reset==0)
        {
             $userId = $id;
        
             return view('admin.users.resetpassword',compact('userId'));
        }else
        {
             return view('admin.thankyou');
        }
    }

    public function resetPassword(Request $request){

        $this->validate($request, [
            'password' => 'required',
            'password_confirmation' =>'required|same:password'
        ]);

        $password = md5($request->get('password'));
        $user_id = $request->get('id');

        $user = $this->user->find($user_id);
        $user->password = $password;
        $user->is_reset = 1;

        $user->save();

        flash(trans('messages.RESET_PASSWORD'),'success');
        return back();


    }

    public function switchVerification(Request $request){
        $validator = validator()->make($request->all(), [
            'id'   =>'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        $user= $this->user->find($id);
        if($user){
            $newStatus = ($user->is_verified==0)?1:0;
            $user->is_verified=$newStatus;
            $user->save();
            // Get New updated Object of User
            $updated = $user;

            if($request->wantsJson()){
                return response([
                    "data"        =>$this->userTransformer->transform($updated),
                    "message"     =>trans('messages.user-verifed-status',["status"=>($newStatus==1)?"verified":"unverified"]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.user-verifed-status',["status"=>($newStatus==1)?"verified":"unverified"]),'success');
            return back();
        }
        flash(trans('messages.user-update-fail'),'error');
        return back();
    }
}
