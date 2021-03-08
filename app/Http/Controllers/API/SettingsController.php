<?php

namespace App\Http\Controllers\API;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



use App\Mail\SMSSender;

class SettingsController extends Controller
{
   
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    public function __construct()
    {   
       $this->middleware('auth:admin');
    
    }
    
    protected function updateSMSInfos(Request $request){
       
        Validator::make($request->all(), [
            'sms-username' => ['required', 'string'],
            'sms-password' => ['required', 'string'],
            'sms-host' => ['required', 'string'],
            'sms-port' => ['required', 'string','max:4','min:2'],
            'sms-senderId' => ['required', 'string', 'min:3'],
            // 'test-phone' => ['string', 'min:10','regex:/(0)(5|6|7)[0-9]{8}/'],
        ])->validate();

        try{

            if(DB::table('parametres_sms')->count()==0){

                DB::table('parametres_sms')->insert(
                    [
                        'senderId' => $request['sms-senderId'],
                        'host'=> $request['sms-host'],
                        'port'=> $request['sms-port'],
                        'username'=> $request['sms-username'],
                        'password'=> $request['sms-password']
                    ]

                );

            }else{
                DB::table('parametres_sms')->update(
                    [
                        'senderId' => $request['sms-senderId'],
                        'host'=> $request['sms-host'],
                        'port'=> $request['sms-port'],
                        'username'=> $request['sms-username'],
                        'password'=> $request['sms-password']
                    ]

                );

            }

            if($request->test=="on" || $request->test==true){
                
                try {
                    dd("dsfkjsdhfjkdshdsjh");
                    $sender = new SMSSender(
                        $request['sms-host'],$request['sms-port'],
                        $request['sms-username'],$request['sms-password'],$request['sms-senderId']
                    );
                    $normalized_phone="213".substr($request['test-phone'],1);
                    $res=$sender->Submit("SMS de Test",$normalized_phone,"2");
                    $flag=\explode('|',$res)[0];
                    if($flag!="1701"){
                        return back()
                        ->with(
                            [
                                'sms-flag'=>'success',
                                'sms-message'=>'Paramètres enregistrés avec succès.',
                                'sms-test-flag'=>'fail',
                                'sms-test-message'=>'Test de messagerie échoué ! un ou plusieurs paramètres ne sont pas valides. Code erreur ICOSNET: '.$flag
                                
                                
                                ]
                            );

                    }
                    return back()
                    ->with(
                        [
                            'sms-flag'=>'success',
                            'sms-message'=>'Paramètres enregistrés avec succès.',
                            'sms-test-flag'=>'success',
                            'sms-test-message'=>'Test de messagerie réussi! Vous devez avoir reçu un SMS.'
                        ]
                        );
                } catch (\Throwable $th) {
                    dd($th);
                    return back()
                    ->with(
                        [
                            'sms-flag'=>'success',
                            'sms-message'=>'Paramètres enregistrés avec succès.',
                            'sms-test-flag'=>'fail',
                            'sms-test-message'=>'Test de messagerie échoué! Vérifiez bien vos paramètres ou contactez le fournisseur de service.'
                            
                            
                            ]
                        );

                }

            }

            return back()
            ->with(
                [
                    'sms-flag'=>'success',
                    'sms-message'=>'Paramètres enregistrés avec succès.'
                ]
                );
            
            
        }catch(\Throwable $e){
            return back()->withInput($request->input())
            ->with(
                [
                    'sms-flag'=>'fail',
                    'sms-message'=>'Une erreur s\'est produite. Impossible d\'enregistrer les paramètres.'
                    
                    
                    ]
            );
                
        }
            

        


    }



    protected function updateNotifPreferences(Request $request){
       
        $email_prefs=[];
        $sms_prefs=[];

        foreach ($request->all() as $key => $value) {
            if($value==true || $value=="on"){
                if(\str_starts_with($key,'email-'))
                array_push($email_prefs,\str_replace('email-','',$key));
                if(\str_starts_with($key,'sms-'))
                array_push($sms_prefs,\str_replace('sms-','',$key));
            }
        }
       
        try{

            DB::table('parametres_notifications')
            ->update(
               [
                   'events_via_email'=>\implode(',',$email_prefs),  
                   'events_via_sms'=>\implode(',',$sms_prefs)  
               ]
            );
            
        }catch(Exception $e){
            return back()->withInput($request->input())
            ->with(
                [
                    'flag-events'=>'fail',
                    'message-events'=>'Une erreur s\'est produite. Impossible de mettre à jours les paramètres.'

                    
                ]
                );

        }

        return back()
        ->with(
            [
                'flag-events'=>'success',
                'message-events'=>'Préférences de notification mises à jours avec succès.'
            ]
            );


    }
    
 
    

}
