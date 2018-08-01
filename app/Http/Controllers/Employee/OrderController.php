<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
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


        return view('employee.inventory.orders');
        
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
            'product_id'   =>'required',
            'supplier_id'  => 'required',
            'quantity_total' => 'required',
            
        ]);
        
        if ($validator->fails()) {

            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }
        else{
            $input = array_only($request->all(),["product_id","supplier_id","quantity_total"]);
            $orders = Orders::create($input);   
            
        }
        return back()->with('success', 'Order added successfully!!');
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
    public function update(Request $request, $order_id)
    {
        $order=Orders::findOrFail($order_id);
        $validator = validator()->make($request->all(), [
            'product_id'   =>'required',
            'supplier_id'  => 'required',
            'quantity_total' => 'required',
            
        ]);
        
        if ($validator->fails()) {

            // /echo "here"; exit;
            flash(trans('messages.parameters-fail-validation'),'danger');
            return back()->withErrors($validator)->withInput();
        }
        else{
            
         // SET AND UPDATE
    
        $order->product_id   = $product_id;
        $order->supplier_id  =  $supplier_id;
        $order->quantity_total =  $quantity_total;
        $order->save();
            
     
        
        }
        return back()->with('success', 'Order details updated successfully!!');
    

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
