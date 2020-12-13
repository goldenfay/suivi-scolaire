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
        // $this->middleware('auth:prof');
        $this->user=new \stdClass();
        $this->user->prof= Prof::find(1)->get()->first();
        $this->fetchProfData();
        
        
        

       
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
    public function teaching($classeId=null)
    {   $classe=null;
        if($classeId==null)
            $classe=reset($this->user->classes);
        else $classe=$this->user->classes["$classeId"];        
        // dd($this->user->formations);
        return view('prof.teaching',[
            "currentClasse"=> $classe,
            "user"=> $this->user
        
            ]);
    }

    public function showAddObservationView($eleveId){
        $eleve=DB::table('eleve')
        ->find($eleveId)
        ;
        if($eleve==null) return abort(404);
        
        $observations=DB::table('observation')
        ->where('Eleve',$eleve->Id)
        ->where('Professeur',$this->user->prof->Id)
        ->whereYear('Date','=',Date('Y'))
        ->orderby('Date','Desc')
        ->get();

        return view('prof.cahier-correspond',[
            "observations"=> $observations,
            "user"=> $this->user,
            "eleve"=> $eleve
        
            ]);
    }

    public function fetchProfData(){
        // Fetch all prof classes
        $prof_classes= DB::table('professeur_classe')
        ->where('Professeur',$this->user->prof->Id)
        ->leftjoin('classe','Classe','classe.Id')
        ->select('classe.*')
        ->get();
            // Fetch all prof formations
        $prof_formations= DB::table('professeur_formation')
        ->where('Professeur',$this->user->prof->Id)
        ->leftjoin('formation','Formation','formation.Id')
        ->select('formation.*')
        ->get();
            // Fetch all prof observations(correspondances)
        $prof_observations= DB::table('observation')
        ->where('Professeur',$this->user->prof->Id)
        ->selectRaw('Type, Etat, COUNT(*) as Count')
        ->groupBy('Type','Etat')
        ->get();

        
        $this->user->observationsCounts=$prof_observations;
        $this->user->classes=array();
        $this->user->formations=array();
            // Populate classes with subscribed eleves
        forEach($prof_classes as $classe) {
            $this->user->classes[$classe->Id.""]=new \stdClass();
            $this->user->classes[$classe->Id.""]->classe=$classe;
            // Fetch all prof classes
            $this->user->observations_per_eleve= DB::table('eleve')
            ->leftjoin('observation','eleve.Id','observation.Eleve')
            ->where('observation.Professeur',$this->user->prof->Id)
            ->selectRaw('eleve.Id as eleveId, count(observation.Id)as Count')
            // ->selectRaw('eleve.Id, count(*) as Count')
            ->groupBy('eleve.Id')
            ->get()->pluck('Count','eleveId')->toArray()
        ;
        // $observations_per_eleve=$observations_per_eleve->selectRaw('eleveId')->get();
            // $observations_per_eleve=(DB::table('eleve as e')
            // // ->leftjoin('observation','e.Id','observation.Eleve')
            // // ->where('observation.Professeur',$this->user->prof->Id)
            // // ->groupBy('e.Id')
            // // ->selectRaw('e.Id, count(observation.Id) as Count'))->get();
            // ->selectRaw("e.Id, count(select Id from observation where  e.Id=observation.Eleve) as Count"))
            // ->get();
            $eleves_of_classe=DB::table('eleve_classe')
            ->join('eleve','Eleve','eleve.Id')
            ->where('eleve_classe.Classe',$classe->Id)
            ->get();
            $this->user->classes[$classe->Id]->eleves=$eleves_of_classe;
        }
            // Populate formations with subsribed eleves
        forEach($prof_formations as $formation) {
            $this->user->formations[$formation->Id]=new \stdClass();
            
            $this->user->formations[$formation->Id]->formation=$formation;
            // Fetch all prof formations
            $eleves_of_formation= DB::table('eleve_formation')
            ->where('Formation',$formation->Id)
            ->leftjoin('eleve','Eleve','eleve.Id')
            ->select('eleve.*')
            ->get();
            $this->user->formations[$formation->Id]->eleves=$eleves_of_formation;
        }
    }

}
