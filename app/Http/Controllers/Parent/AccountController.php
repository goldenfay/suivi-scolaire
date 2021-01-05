<?php

namespace App\Http\Controllers\Parent;

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
        $this->middleware('auth');


        $this->user=Auth::user();
        
        
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showEditProfileForms()
    {  
            
        return view('parent.account',[
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
        // if($request->email==null && $request->phone==null || $request->actionner==null )
        // return response(json_encode([
        //     "flag" => "fail",
        //     "message" => "Paramètres invalides"
        // ]), 422);
        // $user_check=DB::table('parent')->find($request->actionner);
        // if($user_check==null)
        // return response(json_encode([
        //     "flag" => "fail",
        //     "message" => "Vous n'êtes pas autorisés à effectuer cette tâche"
        // ]), 403);
        $user=Auth::user();
        if($user->Email==$request->email && $user->NumTel==$request->phone)
            return back();

        $changes=[];
        if($request->email!=null && $request->email!=Auth::user()->Email)
        $changes['Email']=$request->email;
        if($request->phone!=null && $request->phone!=Auth::user()->NumTel)
        $changes['NumTel']=$request->phone;

    
        Validator::make($changes, [
            'Email' => ['string', 'email', 'max:255', 'unique:parent'],
            'NumTel' => ['string', 'regex:/^0[5679]\d{8}$/i'],
        ])->validate();
       
        
        try{
            if($changes['Email']!=null || $changes['NumTel']!=null)
                DB::table('parent')->where('id',Auth::user()->id)
                ->update($changes
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

        $user_row=DB::table('parent')
        ->find(Auth::user()->id);

        if(!Hash::check($request->old_password, Auth::user()->password))
            return back()->withInput($request->only('password'))->withErrors(['old_password'=> 'Mot de passe incorrect.']);

        try{
            DB::table('parent')->where('id',Auth::user()->id)
            ->update(['password'=> Hash::make($request->password)]);
          
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
