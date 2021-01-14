<?php

namespace App\Http\Controllers\API;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


use App\Notifications\ParentNotification;
use App\Notifications\ProfNotification;

use App\Mail\AccountConfirmMailer;
use App\Models\ParentEleve;
use App\Models\Prof;

class AccountsController extends Controller
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
    
    protected function updateProfStatus(Request $request,$id){
       
        if($id==null || $request->Etat==null )
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);

        if($request->Etat!="V" && $request->Etat!="R" )
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);

        $prof = Prof::find($id);
        if($prof==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Enseignant non retrouvé"
        ]), 404);
       
  
        try{

          $prof->update(
                ['Etat'=>$request->Etat]
            );
           $prof->save() ;
            
        }catch(Exception $e){
            return response(json_encode()->json([
                "flag" => "fail",
                "message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
              ]), 500);

        }

        
        $civilite=$prof->Cvilite==null?"Mr/Mme":$prof->Cvilite;
            
        $obj=new \stdClass();
        $obj->user="prof";        
        $obj->destinataire=$prof->Nom."  ".$prof->Prenom;        
        $obj->subject="Compte validé";  
      
        try{
            Mail::to($prof->Email)
            ->send(new AccountConfirmMailer($obj));
        }catch(\Throwable $e){
            

        }


        return response(json_encode([
            "flag" => "success",
            "message" => "Compte confirmé avec succès."

        ]), 200);

    }



    protected function updateParentStatus(Request $request,$id){
       
        if($id==null || $request->Etat==null )
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);

        if($request->Etat!="V" && $request->Etat!="R" )
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);

        $parent = ParentEleve::find($id);
        if($parent==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Enseignant non retrouvé"
        ]), 404);
       
        try{

          $parent->update(
                ['Etat'=>$request->Etat]
            );
           $parent->save() ;
            
        }catch(Exception $e){
            return response(json_encode()->json([
                "flag" => "fail",
                "message" => "Une s'est produite au niveau du serveur. Impossible de valider l'action"
              ]), 500);

        }

        
        $civilite=$parent->Cvilite==null?"Mr/Mme":$parent->Cvilite;
            
        $obj=new \stdClass();
        $obj->user="parent";        
        $obj->destinataire=$parent->Nom."  ".$parent->Prenom;        
        $obj->subject="Compte validé";  
      
        try{
            Mail::to($parent->Email)
            ->send(new AccountConfirmMailer($obj));
        }catch(\Throwable $e){
            

        }


        return response(json_encode([
            "flag" => "success",
            "message" => "Compte confirmé avec succès."

        ]), 200);

    }
    
 
    

}
