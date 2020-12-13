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
    {   $report = new MyReport($this->user->prof->id);
        $report->run();
        // $report->getStats($this->user->prof->id);
        
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
        ->where('Eleve',$eleve->id)
        ->where('Professeur',$this->user->prof->id)
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
        ->where('Professeur',$this->user->prof->id)
        ->leftjoin('classe','Classe','classe.id')
        ->select('classe.*')
        ->get();
            // Fetch all prof formations
        $prof_formations= DB::table('professeur_formation')
        ->where('Professeur',$this->user->prof->id)
        ->leftjoin('formation','Formation','formation.id')
        ->select('formation.*')
        ->get();
            // Fetch all prof observations(correspondances)
        $prof_observations= DB::table('observation')
        ->where('Professeur',$this->user->prof->id)
        ->selectRaw('Type, Etat, COUNT(*) as Count')
        ->groupBy('Type','Etat')
        ->get();

        
        $this->user->observationsCounts=$prof_observations;
        $this->user->classes=array();
        $this->user->formations=array();
            // Populate classes with subscribed eleves
        forEach($prof_classes as $classe) {
            $this->user->classes[$classe->id.""]=new \stdClass();
            $this->user->classes[$classe->id.""]->classe=$classe;
            // Fetch all prof classes
            $this->user->observations_per_eleve= DB::table('eleve')
            ->leftjoin('observation','eleve.id','observation.Eleve')
            ->where('observation.Professeur',$this->user->prof->id)
            ->selectRaw('eleve.id as eleveId, count(observation.id)as Count')
            // ->selectRaw('eleve.id, count(*) as Count')
            ->groupBy('eleve.id')
            ->get()->pluck('Count','eleveId')->toArray()
        ;
        // $observations_per_eleve=$observations_per_eleve->selectRaw('eleveId')->get();
            // $observations_per_eleve=(DB::table('eleve as e')
            // // ->leftjoin('observation','e.id','observation.Eleve')
            // // ->where('observation.Professeur',$this->user->prof->id)
            // // ->groupBy('e.id')
            // // ->selectRaw('e.id, count(observation.id) as Count'))->get();
            // ->selectRaw("e.id, count(select id from observation where  e.id=observation.Eleve) as Count"))
            // ->get();
            $eleves_of_classe=DB::table('eleve_classe')
            ->join('eleve','Eleve','eleve.id')
            ->where('eleve_classe.Classe',$classe->id)
            ->get();
            $this->user->classes[$classe->id]->eleves=$eleves_of_classe;
        }
            // Populate formations with subsribed eleves
        forEach($prof_formations as $formation) {
            $this->user->formations[$formation->id]=new \stdClass();
            
            $this->user->formations[$formation->id]->formation=$formation;
            // Fetch all prof formations
            $eleves_of_formation= DB::table('eleve_formation')
            ->where('Formation',$formation->id)
            ->leftjoin('eleve','Eleve','eleve.id')
            ->select('eleve.*')
            ->get();
            $this->user->formations[$formation->id]->eleves=$eleves_of_formation;
        }
    }

}
