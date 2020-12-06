<?php

namespace App\Http\Controllers\Auth;

use App\Models\ParentEleve;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
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
        $this->middleware('guest:parent')->except('logout');
    }

    public function showProfLoginForm()
    {
        return view('auth.login', ['url' => 'prof']);
    }

    public function profLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('prof')->attempt(['Email' => $request->email, 'Password' => $request->password], $request->get('remember'))) {

            return redirect()->intended('/prof');
        }
        return back()->withInput($request->only('email', 'remember'));
    }


    public function showParentLoginForm()
    {
        return view('auth.login', ['url' => 'parent']);
    }

    public function parentLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
            // dd($request->password,Hash::make($request->password));
        $member = ParentEleve::where('Email', $request->email)->first();
        // dd($member);
        if (Hash::check($request->password, $member->Password, [])) {
            
            return redirect()->intended(route('prof.reports'));
        }    
       
        // if (Auth::guard('parent')->attempt(['Email' => $request->email, 'Password' => $request->password], $request->get('remember'))) {

        //     return redirect()->intended('/parent');
        // }
        return back()->withErrors(['credentials'=> 'Nom d\'utilisateur ou mot de passe invalide']);
    }
}
