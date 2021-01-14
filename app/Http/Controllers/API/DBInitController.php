<?php

namespace App\Http\Controllers\API;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


use App\Models\ParentEleve;
use App\Models\Prof;
use App\Models\Eleve;

class DBInitController extends Controller
{
    // protected $user;
   
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    public function __construct()
    {   
       $this->middleware('auth:admin');
    
    }

    /**
     * Affect a/many classe(s) to a formation
     */
    protected function affectClasseFormation(Request $request){
        if($request->formation==null || $request->classes==null || empty($request->classes) )
            return back()->with([
            "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
                ]);
                
        $formation=DB::table('formation')->find($request->formation);
        if($formation==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
                ]);
            
        foreach ($request->classes as $idx => $classeId) {
            $classe=DB::table('classe')->find($classeId);
            if($classe==null)
            return back()->with([
                "affectation-flag" => "fail",
                    "affectation-message" => "Paramètres invalides"
                    ]);
        }    
        
        
        try{
            foreach ($request->classes as $idx => $classeId) {
                $exists=DB::table('classe_formation')
                ->where('Classe',$classeId)
                ->where('Formation',$request->formation)
                ->get();
                if($exists==null || $exists->count()==0)
                DB::table('classe_formation')
                ->insert([
                    "Classe"=>$classeId,
                    "Formation"=>$request->formation,
                    ]);
                
                }    

                
          return back()->with([
              "affectation-flag" => "success",
              "affectation-message" => "Affectation réussie."
              
              ]);
              
              
            }catch(Exception $e){
                return back()->with()([
                    "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
                ]);

            }
            
            
    }
    /**
     * Affect a/many matiere(s) to a formation
     */
    protected function affectMatiereFormation(Request $request){
        if($request->formation==null || $request->matieres==null || empty($request->matieres) )
            return back()->with([
            "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
                ]);
                
        $formation=DB::table('formation')->find($request->formation);
        if($formation==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
                ]);
            
        foreach ($request->matieres as $idx => $matiereId) {
            $matiere=DB::table('matiere')->find($matiereId);
            if($matiere==null)
            return back()->with([
                "affectation-flag" => "fail",
                    "affectation-message" => "Paramètres invalides"
                    ]);
        }    
        
        
        
        try{
            foreach ($request->matieres as $idx => $matiereId) {
                $exists=DB::table('matiere_formation')
                ->where('Matiere',$matiereId)
                ->where('Formation',$request->formation)
                ->get();
                if($exists==null || $exists->count()==0)
                DB::table('matiere_formation')
                ->insert([
                    "Matiere"=>$matiereId,
                    "Formation"=>$request->formation,
                    ]);
                
                }    

                
          return back()->with([
              "affectation-flag" => "success",
              "affectation-message" => "Affectation réussie."
              
              ]);
              
              
            }catch(Exception $e){
                return back()->with()([
                    "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
                ]);

            }
            
            
    }
    
    /**
     * Affect a prof to a classe , with a metiere
     */
    protected function affectProfClasse(Request $request){
       
        if($request->enseignant==null || $request->classe==null || $request->matiere==null )
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);

        $prof=DB::table('professeur')->find($request->enseignant);
        if($prof==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        $classe=DB::table('classe')->find($request->classe);
        if($classe==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        $matiere=DB::table('matiere')->find($request->matiere);
        if($matiere==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        $prof=$prof;    
        $matiere=$matiere;    
        $classe=$classe;    


        try{

          DB::table('professeur_classe')
          ->insert([
              "Professeur"=>$request->enseignant,
              "Classe"=>$request->classe,
              "Matiere"=>$request->matiere,
          ]);

          return back()->with([
            "affectation-flag" => "success",
            "affectation-message" => "Affectation réussie."

        ]);

            
        }catch(Exception $e){
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
              ]);

        }


    }
    
    
    /**
     * Affect a prof to a formation 
     */
    protected function affectProfFormation(Request $request){
       
        if($request->enseignant==null || $request->formation==null )
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);

        $prof=DB::table('professeur')->find($request->enseignant);
        if($prof==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        $formation=DB::table('formation')->find($request->formation);
        if($formation==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        
        $prof=$prof;    
        $formation=$formation;    
        


        try{

          DB::table('professeur_formation')
          ->insert([
              "Professeur"=>$request->enseignant,
              "Formation"=>$request->formation,
          ]);

          return back()->with([
            "affectation-flag" => "success",
            "affectation-message" => "Affectation réussie."

        ]);

            
        }catch(Exception $e){
            return back()->with()([
                "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
              ]);

        }


    }
    
    
    /**
     * Affect a parent to his children 
     */
    protected function affectParentChildren(Request $request){
        if($request->parent==null || $request->children==null || empty($request->children) )
        return back()->with([
            "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
                ]);
                
                $prof=DB::table('parent')->find($request->parent);
                if($prof==null)
                return back()->with([
                    "affectation-flag" => "fail",
                    "affectation-message" => "Paramètres invalides"
                    ]);
                    
                    foreach ($request->children as $idx => $eleveId) {
                        $eleve=DB::table('eleve')->find($eleveId);
            if($eleve==null)
            return back()->with([
                "affectation-flag" => "fail",
                    "affectation-message" => "Paramètres invalides"
                    ]);
        }    
        
        
        
        try{
            foreach ($request->children as $idx => $eleveId) {
                $exists=DB::table('eleve_parent')
                ->where('Eleve',$eleveId)
                ->where('Parent',$request->parent)
                ->get();
                if($exists==null || $exists->count()==0)
                DB::table('eleve_parent')
                ->insert([
                    "Eleve"=>$eleveId,
                    "Parent"=>$request->parent,
                    ]);
                
                }    

                
          return back()->with([
              "affectation-flag" => "success",
              "affectation-message" => "Affectation réussie."
              
              ]);
              
              
            }catch(Exception $e){
                return back()->with()([
                    "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
                ]);

            }
            
            
        }
        
        
        

    /**
     * Affect a eleve to a formation 
     */
    protected function affectEleveFormation(Request $request){
        
        if($request->eleve==null || $request->formation==null )
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);

        $eleve=Eleve::find($request->eleve);
        if($eleve==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        $formation=DB::table('formation')->find($request->formation);
        if($formation==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        


        try{

            DB::table('eleve_formation')
            ->insert([
                "Eleve"=>$request->eleve,
                "Formation"=>$request->formation,
            ]);

            return back()->with([
            "affectation-flag" => "success",
            "affectation-message" => "Affectation réussie."

        ]);

            
        }catch(Exception $e){
            return back()->with()([
                "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
                ]);

        }


    }


    /**
     * Affect a eleve to a classe 
     */
    protected function affectEleveClasse(Request $request){
       
        if($request->eleve==null || $request->classe==null )
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);

        $eleve=Eleve::find($request->eleve);
        if($eleve==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
        $classe=DB::table('classe')->find($request->classe);
        if($classe==null)
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Paramètres invalides"
            ]);
         


        try{

          DB::table('eleve_classe')
          ->insert([
              "Eleve"=>$request->eleve,
              "Classe"=>$request->classe,
          ]);

          return back()->with([
            "affectation-flag" => "success",
            "affectation-message" => "Affectation réussie."

        ]);

            
        }catch(Exception $e){
            return back()->with([
                "affectation-flag" => "fail",
                "affectation-message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
              ]);

        }


    }
}
    