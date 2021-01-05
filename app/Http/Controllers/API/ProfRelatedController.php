<?php

namespace App\Http\Controllers\API;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;


use App\Models\Observation;
use App\Models\Prof;

class ProfRelatedController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
       $this->middleware('auth:prof');

       
    }
    
    protected function markAsRead(Request $request,$notifId){
       
        if($notifId==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);
         
        
        try{
            Auth::guard('prof')->user()->notifications()->where('id',$notifId)->first()->markAsRead();
            
         
        }catch(Exception $e){
            return response(json_encode()->json([
                "flag" => "fail",
                "message" => "Une s'est produite au niveau du serveur. Impossible de modifier l'état de cette notification"
              ]), 500);

        }


        return response(json_encode([
            "flag" => "success",
            "message" => "succès."

        ]), 200);

    }
    
    

    

}
