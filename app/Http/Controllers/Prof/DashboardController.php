<?php

namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Reports\Prof\MyReport;
use App\Models\Prof;

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
        $this->user=new \stdClass();
        $this->user->prof= Prof::find(1)->get()->first();
        
        
        

       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {   $report = new MyReport($this->user->prof->Id);
        $report->run();
        // $report->getStats($this->user->prof->Id);
        
        return view('prof.dashboard',[
            "user"=> $this->user,
            "report"=> $report
            
            ]);
    }
    /**
     * Show the following analysis view.
     *
     * @return \Illuminate\View\View
     */
    public function teaching()
    {   
        // // Fetch all prof classes
        // $prof_classes= DB::table('professeur_classe')
        // ->where('Professeur',$this->user->prof->Id)
        // ->leftjoin('classe','Classe','classe.Id')
        // ->select('classe.*')
        // ->get();
        //     // Fetch all prof formations
        // $prof_formations= DB::table('professeur_formation')
        // ->where('Professeur',$this->user->prof->Id)
        // ->leftjoin('formation','Formation','formation.Id')
        // ->select('formation.*')
        // ->get();
        
        
        // $this->user->classes=array();
        // $this->user->formations=array();
        //     // Populate classes with subscribed eleves
        // forEach($prof_classes as $classe) {
        //     $this->user->classes[$classe->Id]=new \stdClass();
        //     $this->user->classes[$classe->Id]->classe=$classe;
        //     // Fetch all prof classes
        //     $eleves_of_classe= DB::table('eleve_classe')
        //     ->where('Classe',$classe->Id)
        //     ->leftjoin('eleve','Eleve','eleve.Id')
        //     ->select('eleve.*')
        //     ->get();
        //     $this->user->classes[$classe->Id]->eleves=$eleves_of_classe;
        // }
        //     // Populate formations with subsribed eleves
        // forEach($prof_formations as $formation) {
        //     $this->user->formations[$formation->Id]=new \stdClass();
            
        //     $this->user->formations[$formation->Id]->formation=$formation;
        //     // Fetch all prof formations
        //     $eleves_of_formation= DB::table('eleve_formation')
        //     ->where('Formation',$formation->Id)
        //     ->leftjoin('eleve','Eleve','eleve.Id')
        //     ->select('eleve.*')
        //     ->get();
        //     $this->user->formations[$formation->Id]->eleves=$eleves_of_formation;
        // }


        return view('prof.teaching',[
           
            ]);
    }

}
