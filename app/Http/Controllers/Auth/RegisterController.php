<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\ParentEleve;
use App\Models\Prof;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
        $this->middleware('guest:parent');
    }


    public function showProfRegisterForm()
    {
        return view('auth.register-prof', ['url' => 'prof']);
    }

    public function showParentRegisterForm()
    {
        return view('auth.register', ['url' => 'parent']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, String $model="parent" )
    {   if($model=="prof")
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'numTel' => ['required', 'string', 'max:10', 'min:10'],
            'age' => ['required', 'number', 'min:25','max:60', 'min:10'],
            'adresse' => ['required', 'string', 'min:10', 'min:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:parent'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'numTel' => ['required', 'string', 'max:10', 'min:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:parent'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\ParentEleve
     */
    protected function create(array $data)
    {
        return ParentEleve::create([
            'Nom' => $data['nom'],
            'Prenom' => $data['prenom'],
            'NumTel' => $data['numTel'],
            'Email' => $data['email'],
            'Password' => Hash::make($data['password']),
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\ParentEleve
     */
    protected function createProf(Request $request)
    {   $this->validator($request->all())->validate();
         Prof::create([
            'Nom' => $request['nom'],
            'Prenom' => $request['prenom'],
            'NumTel' => $request['numTel'],
            'Email' => $request['email'],
            'Password' => Hash::make($request['password']),
        ]);
        return redirect()->intended('login/prof');
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $request
     * @return \App\Models\ParentEleve
     */
    protected function createParent(Request $request)
    {
         ParentEleve::create([
            'Nom' => $request['nom'],
            'Prenom' => $request['prenom'],
            'NumTel' => $request['numTel'],
            'Email' => $request['email'],
            'Password' => Hash::make($request['password']),
        ]);
        return redirect()->intended('login/parent');
    }
}
