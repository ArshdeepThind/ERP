<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor = Vendor::all();
        return view('home',compact('vendor'));
    }
}
