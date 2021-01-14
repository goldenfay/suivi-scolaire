<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\ParentEleve;
use App\Models\Prof;
use App\Mail\WelcomeMailer;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:prof');
        $this->middleware('guest:admin');
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
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'numTel' => ['required', 'string', 'max:10', 'min:10'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:parent'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $obj=new \stdClass();
        $obj->user="parent";        
        $obj->destinataire=$data["nom"]."  ".$data["prenom"];        
        $obj->subject="Inscription réussie";        
        try{
            Mail::to($data['Email'])
            ->send(new WelcomeMailer($obj));



        }catch(\Throwable $e){

        }


        return ParentEleve::create([
            'Nom' => $data['nom'],
            'Prenom' => $data['prenom'],
            'NumTel' => $data['numTel'],
            'Email' => $data['Email'],
            'password' => Hash::make($data['password']),
        ]);
    }


    /**
     * Show prof registration form.
     *
     */
    protected function showProfRegisterForm (){

        return view('auth.register-prof', ['url'=>'prof']);

    }

    /**
     * Create a new prof.
     *
     * @param  request  $data
     */
    protected function registerProf(Request $request)
    {
        Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'age' => ['required', 'integer',],
            'adresse' => ['required', 'string', 'max:100'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:professeur'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ])->validate();


        $data=$request->all();
        try{

            Prof::create([
                'Nom' => $data['nom'],
                'Prenom' => $data['prenom'],
                'Adresse' => $data['adresse'],
                'Age' => $data['age'],
                'Email' => $data['Email'],
                'password' => Hash::make($data['password']),
            ])->save();


        }catch(\Throwable $e){
            return back()->withInput($request->input())
            ->with(
                [
                    'flag'=>'fail',
                    
                ]
                );

        }

       
        $obj=new \stdClass();
        $obj->user="prof";        
        $obj->destinataire=$data["nom"]."  ".$data["prenom"];        
        $obj->subject="Inscription réussie";        
        try{
            Mail::to($data['Email'])
            ->send(new WelcomeMailer($obj));
        }catch(\Throwable $e){

        }
        return back()
            ->with(
                [
                    'flag'=>'success',
                ]
                );

       
    }
}
