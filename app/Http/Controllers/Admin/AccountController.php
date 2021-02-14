<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use \ParagonIE\Halite\KeyFactory;

use App\Models\Admin;

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
        // $this->middleware('auth:admin');


        // $this->user=Auth::guard('admin')->user();
        
        
       
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

        $user_row=DB::table('admin')
        ->find(Auth::guard('admin')->user()->id);

        if(!Hash::check($request->old_password, Auth::guard('admin')->user()->password))
            return back()->withInput($request->only('password'))->withErrors(['old_password'=> 'Mot de passe incorrect.']);

        try{
            DB::table('admin')->where('id',Auth::guard('admin')->user()->id)
            ->update(['password'=> Hash::make($request->password)]);
          
            return back()->with([
                'flag-password'=>'success',
                'message-password'=> 'Compte mis à jours a vec succès.'
                ]);

        }catch(Exception $e){
            return back()->with([
                'flag-password'=>'fail',
                'message-password'=> 'Une erreur s\'est produite. Impossible de mettre à jours vos infos.'
                ]);

        }
            
    }


}
