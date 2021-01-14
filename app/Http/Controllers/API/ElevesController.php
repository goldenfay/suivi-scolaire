<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Eleve;
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
   
    
    protected function add(Request $request){

        Validator::make($request->all(), [
            'civilite' => ['required', 'exists:civilite,id'],
            'nom' => ['required', 'string', 'min:3'],
            'prenom' => ['required', 'string', 'min:3'],
            'adresse' => ['required', 'string', 'max:300'],
            'dateN' => ['required', 'date','before:'.date('Y-m-d',strtotime('-5 year'))],
            'maladie' => ['nullable','exists:maladie,id'],
            'formation' => ['nullable', 'exists:formation,id'],
            'classe' => ['nullable', 'exists:classe,id'],
        ])->validate();


        try{
            $eleve=Eleve::create([
                'Nom'=>$request->nom,
                'Prenom'=>$request->prenom,
                'Adresse'=>$request->adresse,
                'Date_Naissance'=>$request->dateN,
                'Age'=>date("Y")-date("Y",strtotime($request->dateN)),
                'Maladie'=>$request->maladie,
                'Autre'=>$request->autre,
            ]);
            $eleve->save();

            if($request->formation!=null)
                DB::table('eleve_formation')
                ->insert([
                    'Eleve'=>$eleve->id,
                    'Formation'=>$request->formation,
                ]);

            if($request->classe!=null)
                DB::table('eleve_classe')
                ->insert([
                    'Eleve'=>$eleve->id,
                    'Classe'=>$request->classe,
                ]);

            return back()
            ->with(
                [
                    'register-flag'=>'success',
                    'register-message'=>'Elève inscrit avec succès.'
                ]
                );

        }catch(\Throwable $e){
            // dd($e);
            return back()->withInput($request->input())
            ->with(
                [
                    'register-flag'=>'fail',
                    'register-message'=>'Une erreur s\'est produite. Impossible d\'jouter cet élève.'

                    
                ]
                );

        }

       
        

    }
    protected function update(Request $request,$id){
       
        

    }
    protected function delete(Request $request,$id){

        try{
            DB::table('eleve')->delete(
                ["id"=>$id]
            );
            return response(json_encode(["flag" => "success"]), 200);


        }catch(\Throwable $e){
            // dd($e);
            return response(json_encode(["flag" => "fail"]), 500);
        }
       
        

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
