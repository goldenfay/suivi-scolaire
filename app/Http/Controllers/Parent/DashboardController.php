<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;
use Auth;
use App\Models\ParentEleve;

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
        $this->middleware('auth');


        $this->user=new \stdClass();
        
        
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {  
        if(!property_exists($this->user,"parent"))
            $this->fetchParentData();
        
            

        $nbr_formations=DB::table('eleve_formation as EF')
        ->leftjoin('eleve as E','EF.Eleve','E.id')
        ->leftjoin('eleve_parent as EV','E.id','EV.Eleve')
        ->where('Parent',$this->user->parent->id)
        ->select('EF.Formation')->get()
        ->unique()
        ->count();
        $week_observations=DB::table('observation')
        ->whereIn('Eleve',array_values($this->user->childrenIds))
        ->whereRaw('Date >= DATE(NOW()) - INTERVAL 7 DAY')
        ->selectRaw('Type,Etat, Count(*) as Count')
        ->groupBy('Type','Etat')
        ->get();
        $upcomming_evals=DB::table('planning_examens as P')
        ->whereRaw('Date >= DATE(NOW())')
        ->join('eleve_classe as EC','P.Classe','EC.Classe')
        ->whereIn('EC.Eleve',array_values($this->user->childrenIds))
        ->select('P.*')
        ->get()
        ->unique();
        $pending_observations=DB::table('observation')
        ->whereIn('Eleve',array_values($this->user->childrenIds))
        ->where('Etat', '!=', 'VAL')
        
        ->count();
        // dd($week_observations);  

        
        return view('parent.dashboard',[
            "user"=> $this->user,
            "nbr_formations"=> $nbr_formations,
            "week_observations"=> $week_observations,
            "upcomming_evals"=> $upcomming_evals,
            "pending_observations"=> $pending_observations,
            
            ]);
    }
    /**
     * Show the following analysis view.
     *
     * @return \Illuminate\View\View
     */
    public function enfants($eleveId=null,$classeId=null)
    {   
        if(!property_exists($this->user,"parent"))
            $this->fetchParentData();
        // $eleveId=(int)($eleveId)."";
        // $classeId=(int)($classeId);
        // dd($this->user);
        $eleve=null;
        if($eleveId==null)
            $eleve=reset($this->user->children);
        else $eleve=$this->user->children[$eleveId.""];
        
        if($eleve==null)
            return abort(404);
        $classe=null;
        if($classeId==null)
            $classe=$this->user->children[$eleve->eleve->id.""]->classes->first();
        else $classe=$this->user->children[$eleve->eleve->id.""]->classes->where('id',$classeId)->first();
        
        if($classe==null)
            return abort(404);
            // Fetch for his classes schedules
        $schedule=DB::table('emplois_temps')
        ->where('Classe',$classe->id)
        ->leftjoin('matiere','Matiere','matiere.id')
        ->select('emplois_temps.*','matiere.Code as CodeM','matiere.Des as DesM')
        ->get()
        // ->sort(function($a,$b){
            
        //     return strcasecmp($a->Heure,$b->Heure);
        // })
        ->sort(function($a,$b){
            $daysOrder=["Dimanche","Lundi","Mardi","Mercredi","Jeudi"];
            $daysDiff=array_search($a->Jour,$daysOrder) - array_search($b->Jour,$daysOrder);
            return $daysDiff!=0?$daysDiff:strcasecmp($a->Heure,$b->Heure);
        })
        ;
            // Fetch for his classes programmes
        $programme=DB::table('observation')
        ->where('Eleve',$eleve->eleve->id)
        // ->whereYear('Date','=',Date('Y'))
        ->leftjoin('professeur','Professeur','professeur.id')
        ->select('observation.*','professeur.Nom as NomProfesseur','professeur.Prenom as PrenomProfesseur')
        ->orderby('Date','Desc')
        ->get();
            // Fetch for all its observations (correspondances)
        $observations=DB::table('observation')
        ->where('Eleve',$eleve->eleve->id)
        // ->whereYear('Date','=',Date('Y'))
        ->leftjoin('professeur','Professeur','professeur.id')
        ->select('observation.*','professeur.Nom as NomProfesseur','professeur.Prenom as PrenomProfesseur')
        ->orderby('Date','Desc')
        ->get();

            // Fetch for all its evaluations (correspondances)
        $evaluations=DB::table('note')
        ->leftjoin('tranche','Tranche','tranche.id')
        ->leftjoin('matiere','Matiere','matiere.id')
        ->where('Eleve',$eleve->eleve->id)
        // ->where('tranche.Mois_Deb',Date('m'))
        // ->where('tranche.Mois_Fin',Date('m'))
        ->select('note.*','matiere.Des as Matiere')
        ->get();

            // Fetch for all its special events 
        $evaluations=DB::table('note')
        ->leftjoin('tranche','Tranche','tranche.id')
        ->leftjoin('matiere','Matiere','matiere.id')
        ->where('Eleve',$eleve->eleve->id)
        // ->where('tranche.Mois_Deb',Date('m'))
        // ->where('tranche.Mois_Fin',Date('m'))
        ->select('note.*','matiere.Des as Matiere')
        ->get();
        return view('parent.enfants',[
            "user"=> $this->user->parent,
            "children"=> $this->user->children,
            "eleve"=> $eleve,
            "currentClasse"=> $classe,
            "schedule"=> $schedule,
            "observations"=> $observations,
            "evaluations"=> $evaluations
            
            ]);
    }


    protected function fetchParentData(){
        $this->user->parent = Auth::user();

        $eleves_ids=DB::table('eleve_parent')
        ->where('Parent',$this->user->parent->id)
        ->select('Eleve')
        ->get()
        ->values()->toArray(); 
        $users_eleves_ids=array_map(function ($el){
            return $el->Eleve;

        },$eleves_ids);
        $this->user->childrenIds=$users_eleves_ids;

        $users_children = DB::table('eleve_parent')
        ->where('Parent',$this->user->parent->id)
        ->leftjoin('eleve','Eleve','eleve.id')
        ->leftjoin('maladie','Maladie','maladie.id')
        ->select('eleve.*','maladie.Des as Maladie')
        ->get();
        $this->user->children=array();
        forEach($users_children as $child) {
            $this->user->children[$child->id.""]=new \stdClass();
            $this->user->children[$child->id.""]->eleve=$child;
                // Fetch all eleve classes
            $classes= DB::table('eleve_classe')
            ->where('Eleve',$child->id)
            ->leftjoin('classe','Classe','classe.id')
            ->select('classe.*')
            ->get();
                // Fetch all eleve formations
            $formations= DB::table('eleve_formation')
            ->where('Eleve',$child->id)
            ->leftjoin('formation','Formation','formation.id')
            ->select('formation.*')
            ->get();
            $this->user->children[$child->id.""]->classes=$classes;
            $this->user->children[$child->id.""]->formations=$formations;

        }
    }



    /**
     * Show account view.
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {   
        if(!property_exists($this->user,"parent"))
            $this->fetchParentData();

        return view('parent.account',
        [
            "user"=> $this->user->parent,
            "children"=> $this->user->children,
        ]
    );


    }

}
