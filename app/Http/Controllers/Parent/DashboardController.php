<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;

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
        // $this->middleware('auth');


        $this->user=new \stdClass();
        $this->user->parent = ParentEleve::find(2)->get()->first();
        $users_children = DB::table('eleve_parent')
        ->where('Parent',$this->user->parent->Id)
        ->leftjoin('eleve','Eleve','eleve.Id')
        ->leftjoin('maladie','Maladie','maladie.Id')
        ->select('eleve.*','maladie.Des as Maladie')
        ->get();
        $this->user->children=array();
        forEach($users_children as $child) {
            $this->user->children[$child->Id.""]=new \stdClass();
            $this->user->children[$child->Id.""]->eleve=$child;
                // Fetch all eleve classes
            $classes= DB::table('eleve_classe')
            ->where('Eleve',$child->Id)
            ->leftjoin('classe','Classe','classe.Id')
            ->select('classe.*')
            ->get();
                // Fetch all eleve formations
            $formations= DB::table('eleve_formation')
            ->where('Eleve',$child->Id)
            ->leftjoin('formation','Formation','formation.Id')
            ->select('formation.*')
            ->get();
            $this->user->children[$child->Id.""]->classes=$classes;
            $this->user->children[$child->Id.""]->formations=$formations;

        }
        
        

       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {  
        
        return view('parent.dashboard',[
            "user"=> $this->user
            
            ]);
    }
    /**
     * Show the following analysis view.
     *
     * @return \Illuminate\View\View
     */
    public function enfants($eleveId=null,$classeId=null)
    {   
        // $eleveId=(int)($eleveId)."";
        // $classeId=(int)($classeId);
        $eleve=null;
        if($eleveId==null)
            $eleve=reset($this->user->children);
        else $eleve=$this->user->children[$eleveId.""];
        $classe=null;
        if($classeId==null)
            $classe=$this->user->children[$eleve->eleve->Id.""]->classes->first();
        else $classe=$this->user->children[$eleve->eleve->Id.""]->classes->where('Id',$classeId)->first();
            // Fetch for his classes schedules
        $schedule=DB::table('emplois_temps')
        ->where('Classe',$classe->Id)
        ->leftjoin('matiere','Matiere','matiere.Id')
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
        ->where('Eleve',$eleve->eleve->Id)
        ->whereYear('Date','=',Date('Y'))
        ->leftjoin('professeur','Professeur','professeur.Id')
        ->select('observation.*','professeur.Nom as NomProfesseur','professeur.Prenom as PrenomProfesseur')
        ->orderby('Date','Desc')
        ->get();
            // Fetch for all its observations (correspondances)
        $observations=DB::table('observation')
        ->where('Eleve',$eleve->eleve->Id)
        ->whereYear('Date','=',Date('Y'))
        ->leftjoin('professeur','Professeur','professeur.Id')
        ->select('observation.*','professeur.Nom as NomProfesseur','professeur.Prenom as PrenomProfesseur')
        ->orderby('Date','Desc')
        ->get();

            // Fetch for all its evaluations (correspondances)
        $evaluations=DB::table('note')
        ->leftjoin('tranche','Tranche','tranche.Id')
        ->leftjoin('matiere','Matiere','matiere.Id')
        ->where('Eleve',$eleve->eleve->Id)
        // ->where('tranche.Mois_Deb',Date('m'))
        // ->where('tranche.Mois_Fin',Date('m'))
        ->select('note.*','matiere.Des as Matiere')
        ->get();

            // Fetch for all its special events 
        $evaluations=DB::table('note')
        ->leftjoin('tranche','Tranche','tranche.Id')
        ->leftjoin('matiere','Matiere','matiere.Id')
        ->where('Eleve',$eleve->eleve->Id)
        // ->where('tranche.Mois_Deb',Date('m'))
        // ->where('tranche.Mois_Fin',Date('m'))
        ->select('note.*','matiere.Des as Matiere')
        ->get();
        return view('parent.enfants',[
            "user"=> $this->user->parent,
            "children"=> $this->user->children,
            "eleve"=> $eleve,
            "schedule"=> $schedule,
            "observations"=> $observations,
            "evaluations"=> $evaluations
            
            ]);
    }

}
