<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MatieresController extends Controller
{
   
    protected function create(Request $request){
        Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:4'],
            'desc' => ['required', 'string', 'max:40','min:5'],
        ])->validate();

        try{
            DB::table('matiere')->insert([
                'Code' => $request->code,
                'Desc' => $request->desc,
            ]);
            

            return back()
            ->with(
                [
                    'register-flag'=>'success',
                    'register-message'=>'Matière enregistrée avec succès.'
                ]
                );

        }catch(\Throwable $e){
            return back()->withInput($request->input())
            ->with(
                [
                    'register-flag'=>'fail',
                    'register-message'=>'Une erreur s\'est produite. Impossible d\'jouter la matière.'

                    
                ]
                );

        } 



    }
    /**
     * Schedule a matiere in academic schedule for a class
     */
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
