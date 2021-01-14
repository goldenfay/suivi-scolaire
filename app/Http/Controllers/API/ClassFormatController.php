<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Eleve;

class ClassFormatController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        
        
       
    }
   
    /**
     * Add a new Classe
     */
    protected function addClasse(Request $request){

        Validator::make($request->all(), [
            'niveau' => ['required', 'exists:niveau,id'],
            'des' => ['required', 'string', 'min:3','max:20'],
            'code' => ['nullable', 'string','max:4'],
        ])->validate();


        try{
            DB::table('classe')->insert([
                'Niveau'=>$request->niveau,
                'Des'=>$request->des,
                'Code'=>$request->code,
            ]);
            

            return back()
            ->with(
                [
                    'register-flag'=>'success',
                    'register-message'=>'Classe enregistrée avec succès.'
                ]
                );

        }catch(\Throwable $e){
            // dd($e);
            return back()->withInput($request->input())
            ->with(
                [
                    'register-flag'=>'fail',
                    'register-message'=>'Une erreur s\'est produite. Impossible d\'jouter cet élève.'

                    
                ]
                );

        }   

    }


    /**
     * Add a new Formation
     */
    protected function addFormation(Request $request){

        Validator::make($request->all(), [
            'des' => ['required', 'string', 'min:3','max:20'],
            'code' => ['nullable', 'string','max:4'],
        ])->validate();


        try{
            DB::table('formation')->insert([
                'Des'=>$request->des,
                'Code'=>$request->code,
            ]);
            

            return back()
            ->with(
                [
                    'register-flag'=>'success',
                    'register-message'=>'Formation enregistrée avec succès.'
                ]
                );

        }catch(\Throwable $e){
            // dd($e);
            return back()->withInput($request->input())
            ->with(
                [
                    'register-flag'=>'fail',
                    'register-message'=>'Une erreur s\'est produite. Impossible d\'jouter cet élève.'

                    
                ]
                );

        }

       
        

    }
    protected function update(Request $request,$id){
       
        

    }
    protected function delete(Request $request,$id){

        try{
            DB::table('eleve')->delete(
                ["id"=>$id]
            );
            return response(json_encode(["flag" => "success"]), 200);


        }catch(\Throwable $e){
            // dd($e);
            return response(json_encode(["flag" => "fail"]), 500);
        }
       
        

    }
    

    

}
