<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \ParagonIE\Halite\KeyFactory;

use App\Reports\Admin\MyReport;
use Auth;
use App\Models\Admin;
use App\Models\Prof;
use App\Models\ParentEleve;
use App\Models\Eleve;

class DashboardController extends Controller
{
    protected $user;
    protected $report;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->middleware('auth:admin');


        $this->user=Auth::guard('admin')->user();

        
        
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {  

       
        $report = new MyReport();
        $report->run();
        
        return view('admin.dashboard',[
            "user"=> $this->user,
            "report"=> $report
            
            
            ]);
    }
    /**
     * Show classes/formations managing page.
     *
     * @return \Illuminate\View\View
     */
    public function classes()
    {  

        
        $formations=DB::table('formation')
        ->get();
        $niveaux=DB::table('niveau')
        ->get();
        $classes=DB::table('classe')
        ->get();
        $matieres=DB::table('matiere')
        ->get();

       
       
        return view('admin.classes',[
           
            "formations"=> $formations,
            "classes"=> $classes,
            "matieres"=> $matieres,
            "niveaux"=> $niveaux,
            
            
            ]);
    }
    /**
     * Show prof managing page.
     *
     * @return \Illuminate\View\View
     */
    public function enseignants()
    {  

        $enseignants=Prof::get();
        $formations=DB::table('formation')
        ->get();
        $classes=DB::table('classe')
        ->get();
        $matieres=DB::table('matiere')
        ->get();

       
       
        return view('admin.enseignants',[
            "user"=> $this->user,
            "enseignants"=> $enseignants,
            "formations"=> $formations,
            "classes"=> $classes,
            "matieres"=> $matieres,
            
            
            ]);
    }
    
    /**
     * Show parents/eleves managing view.
     *
     * @return \Illuminate\View\View
     */
    public function parents()
    {  

        $parents=ParentEleve::get();
        $formations=DB::table('formation')
        ->get();
        $classes=DB::table('classe')
        ->get();
        $eleves=Eleve::get();

       
       
        return view('admin.parents',[
            "user"=> $this->user,
            "parents"=> $parents,
            "formations"=> $formations,
            "classes"=> $classes,
            "eleves"=> $eleves,
            
            
            ]);
    }


    /**
     * Show eleves managing view.
     *
     * @return \Illuminate\View\View
     */
    public function eleves()
    {  

        
        $formations=DB::table('formation')
        ->get();
        $classes=DB::table('classe')
        ->get();
        $eleves=Eleve::get();
        $maladies=DB::table('maladie')
        ->get();
        $civilites=DB::table('civilite')
        ->get();

       
       
        return view('admin.eleves',[
            "user"=> $this->user,
            "formations"=> $formations,
            "classes"=> $classes,
            "eleves"=> $eleves,
            "maladies"=> $maladies,
            "civilites"=> $civilites,
            
            
            ]);
    }
    


    protected function fetchData(){

        


        
    }




}
