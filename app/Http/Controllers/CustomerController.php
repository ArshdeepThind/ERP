<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;

use App\Vendor;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request,Customer $customer)
    {
    
        $this->validate($request, [
            'name' =>'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'vendors' => 'required|array|min:1'
        ]);

       /* if ($validator->fails()) {
            return back()->withErrors($validator);
        }*/


        $input = $request->all();
        

        $customer->name=$request->get('name');
        $customer->email=$request->get('email');


        $customer->save();

        $vendors = $request->get('vendors');
        foreach ($vendors as $vendor) {
            $vendor = Vendor::find($vendor);
            if($vendor){
                $customer->attachVendor($vendor);
            }
        }


        
        return ['message' => 'Customer Created!'];

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
