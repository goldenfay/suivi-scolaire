<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ElevesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        // $this->middleware('auth');
        
        
       
    }
   
    
    protected function update(Request $request,$id){
       
        

    }
    protected function getPublicInfos($eleveId){
        $eleve=DB::table('eleve')
        ->find($eleveId);

        if($eleve==null) return abort(404);

            // Fetch all eleve classes
        $classes= DB::table('eleve_classe')
        ->where('Eleve',$eleve->id)
        ->leftjoin('classe','Classe','classe.id')
        ->select('classe.*')
        ->get();
            // Fetch all eleve formations
        $formations= DB::table('eleve_formation')
        ->where('Eleve',$eleve->id)
        ->leftjoin('formation','Formation','formation.id')
        ->select('formation.*')
        ->get();
        return view(
            'pages.view-eleve',
            [
                "eleve"=>$eleve,
                "classes"=>$classes,
                "formations"=>$formations
            ]
        );

    }
    protected function getPlanOfProf($profId){
        // $prof_classes=DB::table('professeur_classe')
        // ->where('Professeur',$profId)
        // ->select('Classe')->get();

        $evaluations = DB::table('planning_examens as PE')
        ->leftjoin('professeur_classe as PC','PE.Classe','PC.Classe')
        ->leftjoin('matiere as M','PE.Matiere','M.id')
        ->where('PC.Professeur',$profId)
        ->whereRaw('MONTH(Date)='.date('m'))
        ->select('PE.*','M.Des as Matiere')
        ->get()
        ->unique()
        ->values()
        ;   
        return response(json_encode(["evaluations"=>$evaluations], 200));

    }

    protected function getObservationsOfProf($profId){
        
    }

    public function getObservationsOfElebve($eleveId){

    }

    

}
