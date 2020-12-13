<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class EvaluationsController extends Controller
{
   
    protected function add(Request $request){
        Validator::make($request->all(), [
            'matiere' => ['required', 'integer'],
            'titre' => ['required', 'string', 'max:30'],
            'heure' => ['required', 'string', 'max:11','min:11'],
            'date' => ['required', 'date'],
            'classeId' => ['required', 'integer'],
            'profId' => ['required', 'integer']
        ])->validate();

            // Check validity of foreign keys
        // Check if the prof and the classe exist and prof teach this class
        $rowCheck=DB::table('professeur_classe')
        ->where('Classe',(int)$request->classeId)
        ->where('Professeur',(int)$request->profId);
        if($rowCheck==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Vous n'avez pas le droite pour effectuer cette opération"
        ]), 403);

        $matiere=DB::table('matiere')
        ->where('Id',(int)$request->matiere);
        if($matiere==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);

        $tranche=DB::table('tranche')
        ->where('Mois_Deb','<=',date('m'))
        ->where('Mois_Fin','>=',date('m'));
        
        $heures=explode("-",$request->heure);
        



        try {
            

            DB::table('planning_examens')->insert(
                ['Annee' => null ,
                'Tranche' => $tranche==null?null:$tranche->first()->Id ,
                'Classe' => $request['classeId'] ,
                'Matiere' => $request['matiere'] ,
                'Date' => $request['date'] ,
                'Heure_Debut' => $heures[0] ,
                'Heure_Fin' => $heures[1] 
                ]
            );
            
        }
        catch(Exception $e) {
          
            return back()->with([
                'flag'=>'fail',
                'message'=> 'Une erreur s\'est produite. Impossible de planifier cette épreuve'
                ]);
        }


      
        return back()->with([
            'flag'=>'success',
            'message'=> 'Epreuve planifiée avec succès'
            ]);

    }
    protected function update(Request $request,$id){
       
        if($id==null || $request->eleve==null || $request->actionner==null || $request->Etat==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);
        $observation = DB::table('observation')->find($id);
        if($observation==null)
        return response(json_encode(["flag" => "fail"]), 404);
       
        $eleveId=(int)$request->eleve;
        $parentId=(int)$request->actionner;
            // Check if mentionned leve is the concerned abt this observation
        $obs_eleve_check = DB::table('observation')
        ->where('Eleve',$eleveId)
        ->where('Id',$observation->Id)
        ->get()
        ;
        if($obs_eleve_check==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Vous n'êtes pas autorisés à effectuer cette tâche"
        ]), 403);

     
            // Check if the actionner is the parent of validated eleve
        $parent_check = DB::table('eleve_parent')
        ->where('Eleve',$eleveId)
        ->where('Parent',$parentId)
        ->get()
        ;
        if($parent_check==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Vous n'êtes pas autorisés à effectuer cette tâche"
        ]), 403);

        try{

            DB::table('observation')
            ->where('Id',$observation->Id)
            ->update(
                ['Etat'=>$request->Etat]
            );
        }catch(Exception $e){
            return response(json_encode()->json([
                "flag" => "fail",
                "message" => "Une s'est produite au niveau du serveur. Impossible de modifier l'état de cette observation"
              ]), 500);

        }


        return response(json_encode([
            "flag" => "success",
            "message" => "Observation mise à jours avec succès."

        ]), 200);

    }
    protected function getPlanOfClass($classeId,$profId=null){
        $prof_classes=DB::table('professeur_classe')
        ->where('Professeur',$profId)
        ->where('Classe',$profId);

        if($prof_classes==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Vous n'avez pas le droite pour effectuer cette opération"
        ]), 403);

        
        $evaluations = DB::table('planning_examens as PE')
        ->leftjoin('professeur_classe as PC','PE.Classe','PC.Classe')
        ->leftjoin('matiere as M','PE.Matiere','M.Id')
        ->where('PC.Professeur',$profId)
        ->where('PE.Classe',$classeId)
        ->whereRaw('MONTH(Date)='.date('m'))
        ->select('PE.*','M.Des as Matiere')
        ->get()
        ->unique()
        ->values()
        ;   
        return response(json_encode(["evaluations"=>$evaluations], 200));

    }
    protected function getPlanOfProf($profId){
        // $prof_classes=DB::table('professeur_classe')
        // ->where('Professeur',$profId)
        // ->select('Classe')->get();

        $evaluations = DB::table('planning_examens as PE')
        ->leftjoin('professeur_classe as PC','PE.Classe','PC.Classe')
        ->leftjoin('matiere as M','PE.Matiere','M.Id')
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
