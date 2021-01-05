<?php

namespace App\Http\Controllers;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        // $this->middleware('auth');
        // $this->middleware('auth:prof');
        // $this->middleware('auth:parent');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {   
        
        // return view('dashboard');
        if(Auth::guard('prof')->check())
            return redirect(route('prof.dashboard'));
        if(Auth::check())
            return redirect('dashboard');
            
    }
}
