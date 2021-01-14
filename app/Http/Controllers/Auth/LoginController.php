<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:prof')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Display login page for profs
     */
    public function showProfLoginForm(){
        return view('auth.login-prof');


    }

    /**
     * Display login page for admin
     */
    public function showAdminLoginForm(){
        return view('auth.login-admin');


    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt(['Email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            
            return redirect()->intended(route('dashboard'));
        }
        else
        return back()->withInput($request->only('email', 'remember'))->withErrors(['credentials'=> 'Nom d\'utilisateur ou mot de passe invalide']);
    }
    public function loginProf(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('prof')->attempt(['Email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            
            if(Auth::guard('prof')->user()->Etat!='V') return abort(404);
            return redirect()->intended(route('prof.dashboard'));
        }
        else
        return back()->withInput($request->only('email', 'remember'))->withErrors(['credentials'=> 'Nom d\'utilisateur ou mot de passe invalide']);
    }



    public function loginAdmin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            
            return redirect()->intended(route('admin.dashboard'));
        }
        else
        return back()->withInput($request->only('email', 'remember'))->withErrors(['credentials'=> 'Nom d\'utilisateur ou mot de passe invalide']);
    }
}
