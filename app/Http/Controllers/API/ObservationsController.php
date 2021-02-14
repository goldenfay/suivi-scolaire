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

use App\Notifications\ParentNotification;
use App\Notifications\ProfNotification;
use App\Mail\SMSSender;
use App\Models\Observation;
use App\Models\ParentEleve;
use App\Models\Prof;

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
            'libelle' => ['required', 'string', 'max:50'],
            'corps' => ['required', 'string', 'max:300'],
            'eleveId' => ['required', 'integer'],
            'profId' => ['required', 'integer']
        ])->validate();

        try {
            

            $newId=DB::table('observation')->insertGetId(
                ['Type' => $request['type'] ,
                'Libelle' => $request['libelle'] ,
                'Corps' => $request['corps'] ,
                'Eleve' => $request['eleveId'] ,
                'Professeur' => $request['profId'] 
                ]
            );
            
        }
        catch(\Throwable $e) {
           
            return back()->with([
                'flag'=>'fail',
                'message'=> 'Une erreur s\'est produite. Impossible d\'ajouter cette observation'
                ]);
        }


        $parent_eleve=DB::table('eleve_parent')
        ->where('Eleve',(int)$request->eleveId)
        ->get()
        ;
        
        if($parent_eleve!=null){
            $parent=ParentEleve::find((int)$parent_eleve->first()->Parent);
            $eleve=DB::table('eleve')->find((int)$request['eleveId']);
            $notificationObj=new \stdClass();
            $notificationObj->observationId=$newId;
            $notificationObj->title=$request['type'];
            $notificationObj->eleve=$eleve->Prenom;
            $notificationObj->body=$request['corps'];
            try{
                    // Notify Parent via email and record it into DB
                $parent->notify(new ParentNotification($notificationObj));

            }catch(\Throwable $e){

            }

            // Check if observation type require SMS notification
            $sms_prefs=DB::table('parametres_notifications')
            ->get(['events_via_sms'])
            ->first()
            ->events_via_sms;
       
            // RQ : explicitelly added a first element+',' to ensure that type exists in a position >0
            if(in_array($request->Type,explode(',',",".$sms_prefs))){
                try {
                        // Notify him via SMS also
                        $sms_settings=DB::table('parametres_sms')
                        ->first();
                        $sender=new SMSSender(
                            $sms_settings->host,$sms_settings->port,
                            $sms_settings->username,$sms_settings->password,$sms_settings->senderId
                        );
                        $normalized_phone="+213".substr($parent->NumTel,1);
                        $sms_body="Nouvelle communication(".$request->Type.") pour ".$eleve->Prenom.". Veuillez consulter votre compte pour voir les détails";
                        $res=$sender->Submit($sms_body,$normalized_phone,"2");
                        $flag=\explode('|',$res)[0];
                        if($flag!="1701"){
                            // Do something, like notify admin that SMS params are invalides
    
                        }
        
                   
                    
                }
                catch(\Throwable $e) {
                    
                
                }
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

        if($parentId!=Auth::user()->id)
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
                // If it's a convocation, push notification to prof
            if($obs_eleve_check->first()->Type=="Convocation" && $request->Etat=="VAL"){
                $prof=Prof::find($obs_eleve_check->first()->Professeur);
                $parent=Auth::user();
                $eleve=DB::table('eleve')->find((int)$request['eleveId'])->get()->first();
                $civilite=$parent->Cvilite==null?"Mr/Mme":$parent->Cvilite;
                
                $notificationObj=new \stdClass();
                $notificationObj->observationId=$id;
                $notificationObj->eleve=$eleve->Prenom;
                $notificationObj->subject="Confirmation de la convocation";
                $notificationObj->body="$civilite $parent->Nom vous confirme son présence à propos de la convocation de son enfant $eleve->Prenom";
                try{
                        // Notify Parent via email and record it into DB
                    $prof->notify(new ProfNotification($notificationObj));

                }catch(\Throwable $e){

                }
            }




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
    protected function markAsRead(Request $request,$obsId){
       
        if($obsId==null || $request->id==null)
        return response(json_encode([
            "flag" => "fail",
            "message" => "Paramètres invalides"
        ]), 422);
            // Check validity of the observation
        $observation = DB::table('observation')->find($obsId);
        if($observation==null)
        return response(json_encode(["flag" => "fail"]), 404);
        
        
        try{
            Auth::user()->notifications()->where('id',$request->id)->first()->markAsRead();
            
            DB::table('observation')
            ->where('id',$observation->id)
            ->update(
                ['Etat'=>'V']
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
        return response(json_encode(["observation"=>$observation], 200));

    }

    protected function getObservationsOfProf($profId){
        
    }

    public function getObservationsOfElebve($eleveId){

    }

    

}
