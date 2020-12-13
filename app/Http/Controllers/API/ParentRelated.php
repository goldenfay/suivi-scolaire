<?php

namespace App\Http\Controllers\ParentAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParentEleve;

class ParentRelatedController extends Controller
{
    protected $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        
       
    }
    public function getEleveObservations($eleveId){
        $eleve=$this->user->children[$eleveId];
        $observations=DB::table('observation')
        ->where('Eleve',$eleve->eleve->id)
        ->whereYear('Date','=',Date('Y'))
        ->leftjoin('professeur','Professeur','professeur.id')
        ->select('observation.*','professeur.Nom as NomProfesseur','professeur.Prenom as PrenomProfesseur')
        ->orderby('Date','Desc')
        ->get();
        return response()->json([
            "message" => "records deleted"
          ], 202);
    }

    

}
