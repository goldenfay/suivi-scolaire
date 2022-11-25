<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \ParagonIE\Halite\KeyFactory;
use Illuminate\Support\Facades\Validator;

use App\Reports\Admin\MyReport;
use Auth;
use App\Models\Admin;
use App\Models\Prof;
use App\Models\ParentEleve;
use App\Models\Eleve;
use App\Models\Observation;

class DashboardController extends Controller
{
    protected $user;
    protected $report;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');


        $this->user = Auth::guard('admin')->user();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {


        // $report = new MyReport();
        // $report->run();

        $nbr_parents = DB::table('parent')
            ->count();
        $profs_per_formation = DB::table('formation')
        ->join('professeur_formation','formation.id','=','professeur_formation.Formation')
        ->selectRaw(('
        formation.id as id,formation.Des as NomF, COUNT(*) as Count '))
        ->groupBy('formation.id','formation.Des')
        ->get();
        $eleves_per_formation = DB::table('formation')
        ->join('eleve_formation','formation.id','=','eleve_formation.Formation')
        ->selectRaw(('
        formation.id as id,formation.Des as NomF, COUNT(*) as Count '))
        ->groupBy('formation.id','formation.Des')
        ->get();
        $eleves_per_classe = DB::table('classe')
        ->join('eleve_classe','classe.id','=','eleve_classe.Classe')
        ->selectRaw(('
        classe.id as id,classe.Des as NomC, COUNT(*) as Count '))
        ->groupBy('classe.id','classe.Des')
        ->get();
        $revenues_formation = DB::table('catalogue_formation')
        ->joinSub($eleves_per_formation->toQuery(),'eleves_per_formation','eleves_per_formation.id','=','catalogue_formation.id')
        ->selectRaw(('catalogue_formation.id as id,eleves_per_formation.NomF as NomF, catalogue_formation.Prix* eleves_per_formation.Count as Total '))
        ->groupBy('catalogue_formation.id','eleves_per_formation.NomF')
        ->get();
        return view('admin.dashboard', [
            "user" => $this->user,
            "report" => array(
                "nbr_parents"=>$nbr_parents,
                "profs_per_formation"=>$profs_per_formation,
                "eleves_per_formation"=>$eleves_per_formation,
                "eleves_per_classe"=>$eleves_per_classe,
                "revenues_formation"=>$revenues_formation,
            )


        ]);
    }
    /**
     * Show classes/formations managing page.
     *
     * @return \Illuminate\View\View
     */
    public function classes()
    {


        $formations = DB::table('formation')
            ->get();
        $niveaux = DB::table('niveau')
            ->get();
        $classes = DB::table('classe')
            ->get();
        $matieres = DB::table('matiere')
            ->get();



        return view('admin.classes', [

            "formations" => $formations,
            "classes" => $classes,
            "matieres" => $matieres,
            "niveaux" => $niveaux,


        ]);
    }
    /**
     * Show prof managing page.
     *
     * @return \Illuminate\View\View
     */
    public function enseignants()
    {

        $enseignants = Prof::get();
        $formations = DB::table('formation')
            ->get();
        $classes = DB::table('classe')
            ->get();
        $matieres = DB::table('matiere')
            ->get();



        return view('admin.enseignants', [
            "user" => $this->user,
            "enseignants" => $enseignants,
            "formations" => $formations,
            "classes" => $classes,
            "matieres" => $matieres,


        ]);
    }

    /**
     * Show parents/eleves managing view.
     *
     * @return \Illuminate\View\View
     */
    public function parents()
    {

        $parents = ParentEleve::get();
        $formations = DB::table('formation')
            ->get();
        $classes = DB::table('classe')
            ->get();
        $eleves = Eleve::get();



        return view('admin.parents', [
            "user" => $this->user,
            "parents" => $parents,
            "formations" => $formations,
            "classes" => $classes,
            "eleves" => $eleves,


        ]);
    }


    /**
     * Show eleves managing view.
     *
     * @return \Illuminate\View\View
     */
    public function eleves()
    {


        $formations = DB::table('formation')
            ->get();
        $classes = DB::table('classe')
            ->get();
        $eleves = Eleve::get();
        $maladies = DB::table('maladie')
            ->get();
        $civilites = DB::table('civilite')
            ->get();



        return view('admin.eleves', [
            "user" => $this->user,
            "formations" => $formations,
            "classes" => $classes,
            "eleves" => $eleves,
            "maladies" => $maladies,
            "civilites" => $civilites,


        ]);
    }




    /**
     * Config page auth check
     *
     *  
     */
    public function sysConfigAuth(Request $request)
    {

        Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:4'],
        ])->validate();


        if ($request->password != 'Ifast2022')
            return back()->with([
                'flag-password' => 'fail',
                'message-password' => 'Mot de passe invalide'
            ]);
        else {
            $request->session()->put('access-granted', true);
            return view('admin.sys-settings');
        }
    }

    /**
     * Show app configuration page.
     *
     * @return \Illuminate\View\View
     */
    public function sysConfig(Request $request)
    {


        if ($request->session()->exists('access-granted'))
            return view('admin.sys-settings');
        else {
            $request->session()->put('access-denied', true);
            return view('admin.sys-settings');
        }
    }


    /**
     * Show app configuration page.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {

        $event_types = Observation::get(['Type'])->map(function ($row) {
            return $row->Type;
        })->unique();
        $events_prefs = DB::table('parametres_notifications')
            // ->get(['events_via_email','events_via_sms'])
            ->first();
        $sms_settings = DB::table('parametres_sms')
            ->first();


        return view('admin.settings', [
            "user" => $this->user,
            "types" => $event_types,
            "events_prefs" => $events_prefs,
            "sms_settings" => $sms_settings,



        ]);
    }


    protected function fetchData()
    {
    }
}
