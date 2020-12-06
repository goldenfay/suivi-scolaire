<?php

namespace App\Http\Controllers;
use App\Reports\MyReport;
use App\Models\Ecole;
use App\Models\Eleve;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // for($i=1;$i<10;$i++){
            
        //     Ecole::insert( array(
        //         "nom_ecole"=> "Ecole".$i, 
        //         "adresse"=>" Adress ecole ".$i." Rue a alger",
        //         "telephone"=>"0555101010",
              
        //     ));
        // }
        // for($i=1;$i<100;$i++){
            
        //     Eleve::insert( array(
        //         "nom"=> "Eleve nom ".$i, 
        //         "prenom"=>" Eleve prenom".$i,
        //         "age"=>rand(8,16),
        //         "pension"=> rand(20000,100000),
        //         "ecole_id"=>rand(0,9),
        //         "date_ajout"=>($i%3==0?"2020-10-25":"2020-11-25")
        //     ));
        // }
        // dd(Ecole::get());
        $report = new MyReport;
        $report->run();
        return view("pages.reports",["report"=>$report]);
    }
}
