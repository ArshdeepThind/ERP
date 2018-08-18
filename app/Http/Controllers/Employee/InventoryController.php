<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Products;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
         if(request()->ajax() || request()->wantsJson()){
            $sort= request()->has('sort')?request()->get('sort'):'name';
            $order= request()->has('order')?request()->get('order'):'asc';
            $search= request()->has('searchQuery')?request()->get('searchQuery'):'';
            
            $pdt= \DB::table('products')->select('products.*')
                ->orderBy("$sort", "$order")
                ->paginate(10);
            
            $paginator=[
                'total_count'  => $pdt->total(),
                'total_pages'  => $pdt->lastPage(),
                'current_page' => $pdt->currentPage(),
                'limit'        => $pdt->perPage()
            ];
            return response([
                "data"        =>    $pdt->all(),
                "paginator"   =>    $paginator,
                "status_code" =>    200
            ],200);
        }


        return view('employee.inventory.products');
        
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
        $validator = validator()->make($request->all(), [
            'product_name'   =>'required|max:100|unique:products',
            'product_price'  => 'required',
            'quantity_initial' => 'required',
            'quantity_shipped' => 'required',
            'quantity_left' => 'required',
            'product_category' => 'required',
            'gender' => 'required'
        ]);
        
        if ($validator->fails()) {
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }
        
        $input = array_only($request->all(),["product_name","product_price","quantity_initial","quantity_shipped","quantity_left","product_category","gender"]);
        $input['product_image'] = "https://images.yourstory.com/2016/08/125-fall-in-love.png?auto=compress";
        $input['description'] = "Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser necessi far uniform grammatica, pronunciation e plu sommun paroles.";
        $products = Products::create($input);   
        
        

        if($request->wantsJson()){
            return response([
                "message"     =>trans('Product added successfully!!'),
                "status_code" =>201
            ],201);
        }
        flash(trans('Product added successfully!!'),'success');
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
        $pdt = Products::findOrFail($id);
        $validator = validator()->make($request->all(), [
            'product_name'   =>'required',
            'product_price'  => 'required',
            'quantity_initial' => 'required',
            'quantity_shipped' => 'required',
            'quantity_left' => 'required',
            'product_category' => 'required',
            'gender' => 'required'
        ]);
        
        if ($validator->fails()) {
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }

        extract($request->all());
         // SET AND UPDATE
        $pdt->product_name  =$product_name;
        $pdt->product_price =$product_price;
        $pdt->quantity_initial = $quantity_initial;
        $pdt->quantity_shipped = $quantity_shipped;
        $pdt->quantity_left = $quantity_left;
        $pdt->product_category = $product_category;
        $pdt->gender = $gender;
        $pdt->save();
     
        
        
        if($request->wantsJson()){
            return response([
                "message"     =>trans('Product details updated successfully!!'),
                "status_code" =>201
            ],201);
        }
        flash(trans('Product details updated successfully!!'),'success');
        return back();
        
    

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        $pdt=Products::findOrFail($product_id);
        $pdt->delete();
    }
    public function removeBulkConfirm($product_id)
    {
        $pdt=Products::findOrFail($product_id);
        $pdt->delete();
    }
}
