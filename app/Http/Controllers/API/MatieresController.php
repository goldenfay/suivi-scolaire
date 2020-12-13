<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MatieresController extends Controller
{
   
    protected function add(Request $request){
        Validator::make($request->all(), [
            'matiere' => ['required', 'integer'],
            'titre' => ['required', 'string', 'max:30'],
            'heure' => ['required', 'string', 'max:11','min:11'],
            'date' => ['required', 'date'],
            'classeId' => ['required', 'integer'],
            'profId' => ['required', 'integer']
        ])->validate();


    }
    
    protected function getAll(){

        $matieres = DB::table('matiere')->get();
        if($matieres==null)
        return response(json_encode(["flag" => "fail"], 404));        
        return response(json_encode(["matieres"=>$matieres], 200));

    }

   
    

}
