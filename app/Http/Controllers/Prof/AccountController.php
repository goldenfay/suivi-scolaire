<?php

namespace App\Http\Controllers\Prof;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use \ParagonIE\Halite\KeyFactory;

use App\Models\ParentEleve;

class AccountController extends Controller
{
    protected $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->middleware('auth:prof');


        $this->user=Auth::guard('prof')->user();
        
        
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showEditProfileForms()
    {  
            
        return view('prof.account',[
            "user"=> $this->user
            ]);
    }
    /**
     * Update account request handler
     *
     * @return \Illuminate\View\View
     */
    public function updateInfos(Request $request)
    {   
        $user=Auth::guard('prof')->user();
        if($user->Email==$request->email && $user->Adresse==$request->adress)
            return back();
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:parent'],
            'adress' => ['required', 'string', 'max:400'],
        ])->validate();
        try{
            DB::table('professeur')->where('id',Auth::guard('prof')->user()->id)
            ->update(
                [
                    'Email'=>$request->email,
                    'Adresse'=>$request->adress
                ]
            );

            return back()->with([
                'flag'=>'success',
                'message'=> 'Compte mis à jours a vec succès.'
                ]);

        }catch(Exception $e){
            return back()->with([
                'flag'=>'fail',
                'message'=> 'Une erreur s\'est produite. Impossible de mettre à jours vos infos.'
                ]);

        }

        
    }

    /**
     * Update account password request handler
     *
     * @return \Illuminate\View\View
     */

    protected function updatePassword(Request $request){
        Validator::make($request->all(), [
            'old_password' => ['required', 'string', 'min:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ])->validate();

        $user_row=DB::table('professeur')
        ->find(Auth::guard('prof')->user()->id);

        if(!Hash::check($request->old_password, Auth::guard('prof')->user()->password))
            return back()->withInput($request->only('password'))->withErrors(['old_password'=> 'Mot de passe incorrect.']);

        try{
            DB::table('professeur')->where('id',Auth::guard('prof')->user()->id)
            ->update(['password',Hash::make($request->password)]);
          
            return back()->with([
                'flag_password'=>'success',
                'message_password'=> 'Compte mis à jours a vec succès.'
                ]);

        }catch(Exception $e){
            return back()->with([
                'flag_password'=>'fail',
                'message_password'=> 'Une erreur s\'est produite. Impossible de mettre à jours vos infos.'
                ]);

        }
            
    }


}
