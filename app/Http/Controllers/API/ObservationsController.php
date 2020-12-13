<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;
use App\Notifications\ParentNotification;
use App\Models\Observation;
use App\Models\ParentEleve;

class ObservationsController extends Controller
{
    // protected $user;
    protected $basic;
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    public function __construct()
    {   
        $this->basic  = new \Nexmo\Client\Credentials\Basic('86cbd5aa', 'nHYVTXX4vocqJaRV');
        $this->client = new \Nexmo\Client($this->basic);


       
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'type' => ['required', 'string', 'max:20'],
            'libelle' => ['required', 'string', 'max:30'],
            'corps' => ['required', 'string', 'max:100'],
            'eleveId' => ['required', 'integer'],
            'profId' => ['required', 'integer']
        ]);
    }

    protected function add(Request $request){
       
        Validator::make($request->all(), [
            'type' => ['required', 'string', 'max:20'],
            'libelle' => ['required', 'string', 'max:30'],
            'corps' => ['required', 'string', 'max:300'],
            'eleveId' => ['required', 'integer'],
            'profId' => ['required', 'integer']
        ])->validate();

        try {
            

            DB::table('observation')->insertOrIgnore(
                ['Type' => $request['type'] ,
                'Libelle' => $request['libelle'] ,
                'Corps' => $request['corps'] ,
                'Eleve' => $request['eleveId'] ,
                'Professeur' => $request['profId'] 
                ]
            );
            
        }
        catch(Exception $e) {
           
            return back()->with([
                'flag'=>'fail',
                'message'=> 'Une erreur s\'est produite. Impossible d\'ajouter cette observation'
                ]);
        }


        $parent_eleve=DB::table('eleve_parent')
        ->where('Eleve',$request['eleveId'])
        ->first();
        $parent=ParentEleve::find($parent_eleve->Parent)->first();


        $parent->notify(new ParentNotification($parent));
        if($request->Type=="Convocation"){

            try {
    
                $message = $this->client->message()->send([
                    'to' => "+213555149081",
                    'from' => 'Scolarité',
                    'text' => 'Votre fils vient de recevoir une convocation.'
                ]); 
                
            }
            catch(Exception $e) {
                
               
            }
        }


        return back()->with([
            'flag'=>'success',
            'message'=> 'Observation ajoutée avec succès'
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
        // try{
        //     $key = config('requests.SECRET_KEY');
    
        //     $eleveId=SymmetricCrypto::decrypt($request->eleve, $key);
        //     $parentId=SymmetricCrypto::decrypt($request->actionner, $key);

        // }catch(Exception $e){
        //     return response(json_encode($e),500);

        // }
            $eleveId=(int)$request->eleve;
            $parentId=(int)$request->actionner;
            // Check if mentionned leve is the concerned abt this observation
        $obs_eleve_check = DB::table('observation')
        ->where('Eleve',$eleveId)
        ->where('id',$observation->id)
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
            ->where('id',$observation->id)
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
    protected function getObservation($id){

        $observation = DB::table('observation')->find($id);
        if($observation==null)
        return response(json_encode(["flag" => "fail"], 404));        
        return response(json_encode([$observation->get()->toJson(JSON_PRETTY_PRINT)], 200));

    }

    protected function getObservationsOfProf($profId){
        
    }

    public function getObservationsOfElebve($eleveId){

    }

    

}
