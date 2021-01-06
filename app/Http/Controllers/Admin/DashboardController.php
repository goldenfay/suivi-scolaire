<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;
use Auth;
use App\Models\Admin;

class DashboardController extends Controller
{
    protected $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->middleware('auth:admin');


        $this->user=new \stdClass();
        
        
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {  
        
        
        return view('admin.dashboard',[
            "user"=> $this->user,
            
            
            ]);
    }
    


    protected function fetchData(){
        
    }




}
