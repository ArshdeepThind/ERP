<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Setting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $settings = Setting::get();
        return view('employee.settings.list',compact('settings'));
    }

    public function store(Request $request){

        $settings = Setting::get()->toarray();

        //mprd($settings);
        $validator = validator()->make($request->all(), [
            'publicholiday'   =>'required',
            'commision'  => 'required'
        ]);
        
        if ($validator->fails()) {

            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }

        $input             =    array_only($request->all(),["publicholiday","commision"]);

        for($i=0;$i<count($settings);$i++){

            if($settings[$i]['label']=="publicholiday"){
                $setting_data = Setting::find($settings[$i]['id']);
                $setting_data->value = $input['publicholiday'];
                $setting_data->save();
            }else if($settings[$i]['label']=="commision"){
                $setting_data = Setting::find($settings[$i]['id']);
                $setting_data->value = $input['commision'];
                $setting_data->save();
            }
        }

        $settings = Setting::get();
        flash(trans('Settings updated successfully'),'success');
        return view('employee.settings.list',compact('settings'));
        
        //mprd('here');
    }

    public function setProfileData(Request $request){

        #($request->all());

        $clinicId       = auth()->user()->id;
        $clinic         = User::with('clinicDetail')->find($clinicId)->first();
        $clinicdetail   = ClinicDetail::where('user_id',$clinicId)->first();

        $clinic->first_name     = $request->first_name;
        $clinic->last_name      = $request->last_name;
        $clinic->contact_number = $request->contact_number;
        $clinic->save();

        $clinicdetail->clinic_name    = $request->clinic_name;
        $clinicdetail->address        = $request->address;
        $clinicdetail->city           = $request->city;
        $clinicdetail->country        = $request->country;
        $clinic->save();



        return response([
                "first_name"        => $clinic->first_name,
                "last_name"         => $clinic->last_name,
                'contact_number'    => $clinic->contact_number,
                'clinic_name'       => $clinicdetail->clinic_name,
                'address'           => $clinicdetail->address,
                'city'              => $clinicdetail->city,
                'country'           => $clinicdetail->country,
                "status_code"       => 200,
                "message"           => 'Profile detail updated sucessfully.'
            ],200);

    }

    public function getProfileData()
    {
        $clinicId   = auth()->user()->id; 
        $clinic = User::with('clinicDetail')->find($clinicId)->toarray();
        #$clinic_name = $clinic['clinic_detail']['clinic_name'];
        
        return response([
                "first_name"        => $clinic['first_name'],
                "last_name"         => $clinic['last_name'],
                'contact_number'    => $clinic['contact_number'],
                'clinic_name'       => $clinic['clinic_detail']['clinic_name'],
                'address'           => $clinic['clinic_detail']['address'],
                'city'              => $clinic['clinic_detail']['city'],
                'country'           => $clinic['clinic_detail']['country'],
                "status_code" => 200
            ],200);
    }

    public function changepasseordpage(){
        return view('clinic.password');
    }

    public function checkpassword(Request $request){ 
        $password = auth()->user()->password;
        if (Hash::check($request->old_password, $password))
        {
            return response([
                "status_code" => 200
            ],200);
        }
        return response([
            "message" => trans('api.clinic_password_invalid'),            
            "status_code" => 201
        ],201);
    }

    public function changePassword(Request $request){
        $clinicId   = auth()->user()->id;
        $password = auth()->user()->password;
        $clinic     = User::where('id',$clinicId)->first();
        if (Hash::check($request->old_password, $password))
        {
            $clinic->password     = $request->password;
            $clinic->save();
            return response([
                "message" => 'Password changed successfully.',
                "status_code" => 200
            ],200);
        }
        return response([
                "message" => trans('api.clinic_password_invalid'),            
                "status_code" => 205
            ],205);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData()
    {
        $clinicId = auth()->user()->id;
        $clinic     = User::with('clinicDetail')->find($clinicId)->toarray();
        #mprd($clinic);

        $date_format = $clinic['date_format'];

        switch ($date_format) {
            case "dd-MM-yyyy":
                $dateformat = "option1";
                break;
            case "MM-dd-yyyy":
                $dateformat = "option2";
                break;
            case "dd-MM-yy":
                $dateformat = "option3";
                break;
            default:
                $dateformat = "option4";
        }



        #$dateformat = $clinic['date_format']  == 'dd-MM-yyyy' ? 'option1' : 'option2';
        #mprd($clinic['date_format']);
        return response([
                "preferred_metal"       => $clinic['preferred_metal'],
                "prosthetic_margins"    => $clinic['prosthetic_margins'],
                'delivery'              => $clinic['delivery'],
                'language'              => 'english',
                'dateformat'            => $dateformat,
                "status_code" => 200
            ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setDateFormat(Request $request)
    {
        $clinicId = auth()->user()->id;
        $clinic     = User::where('id',$clinicId)->first();
        #$dateformat = $request->dateformat  == 'option1' ? 'dd-MM-yyyy' : 'MM-dd-yy';

        $date_format = $request->dateformat;

        switch ($date_format) {
            case "option1":
                $dateformat = "dd-MM-yyyy";
                break;
            case "option2":
                $dateformat = "MM-dd-yyyy";
                break;
            case "option3":
                $dateformat = "dd-MM-yy";
                break;
            default:
                $dateformat = "MM-dd-yy";
        }


        $clinic->date_format = $dateformat;
        $clinic->save();

        

        return response([
                'dd'    =>  $dateformat,
                'dateformat'            => $request->dateformat,
                'date_format'     => $clinic->date_format,
                "status_code" => 200
            ],200);
    }

    public function getDefaultData()
    {
        $clinicId                   = auth()->user()->id;
        $clinic                     = User::with('clinicDetail')->find($clinicId)->toarray();
        $preferred_metal_data       =  \DB::select("SELECT id , en_name as name FROM case_metal_ceramic");
        $prosthetic_margins_data    =  \DB::select("SELECT id , en_name as name FROM case_prosthetic_margins") ;
        $delivery_data              =  \DB::select("SELECT id , en_name as name FROM case_delivery where id != 5"); ;
        return response([
                "preferred_metal"           => $clinic['preferred_metal'],
                "prosthetic_margins"        => $clinic['prosthetic_margins'],
                'delivery'                  => $clinic['delivery'],
                'preferred_metal_data'      => $preferred_metal_data,
                'prosthetic_margins_data'   => $prosthetic_margins_data,
                'delivery_data'             => $delivery_data,                
                "status_code" => 200
            ],200);
    }


    public function setData(Request $request)
    {
        $clinicId = auth()->user()->id;
        $clinic     = User::where('id',$clinicId)->first();
        

        if($request->part == 1){
            $clinic->preferred_metal = $request->update_id;
            $clinic->save();
        }
        else if($request->part == 2){
            $clinic->prosthetic_margins = $request->update_id;
            $clinic->save();
        }
        else if($request->part == 3){ 
            $clinic->delivery = $request->update_id;
            $clinic->save();
        }
        
        return response([
                'preferred_metal'     => $clinic->preferred_metal,
                'prosthetic_margins'  => $clinic->prosthetic_margins,
                'delivery'            => $clinic->delivery,
                "status_code" => 200
            ],200);
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
