<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;


use App\Transformers\UsersTransformer;
class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     protected $Transformer;

    public function __construct(UsersTransformer $Transformer)
    {
        
        $this->Transformer = $Transformer;
    }

    public function index()
    {
        $employee_id = auth()->user()->id;
        $employee = Employee::findOrFail($employee_id)->toarray();
        $employee_name = $employee['first_name']." ".$employee['last_name'];


        return view('employee.chat.list', compact('employee_id', 'employee_name'));
    }

    public function cases(Request $request, $id)
    {

         $employee = Employee::select('employees.first_name as receiverName', 'c.receiverID as receiverID')
                ->where('c.conversation_id', $id)
                ->where('c.senderID', auth()->user()->id)
                ->leftJoin('conversation as c', 'c.receiverID', '=', 'employees.id')->first()->toarray();

        return response([
            "receiverName"   => $employee['receiverName'],
            "receiverID" => $employee['receiverID'],
            "status_code" => 200
        ], 200);
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
        //
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
