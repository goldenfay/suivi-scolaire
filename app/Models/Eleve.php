<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Eleve extends Model
{
    use HasFactory;
    protected $table="eleve";
    protected $guarded = ['id'];
    public $timestamps = false;


    public function parents(){

        return DB::table('eleve_parent')
        ->where('Eleve',$this->id)
        ->leftjoin('parent as p','Parent','p.id')
        ->select('p.*')
        ->get()
        ->unique();
    }
    public function classes(){

        return DB::table('eleve_classe')
        ->where('Eleve',$this->id)
        ->leftjoin('classe as c','Classe','c.id')
        ->select('c.*')
        ->get()
        ->unique();
    }

    public function maladie(){

        return DB::table('maladie')
        ->where('id',$this->Maladie)
        ->first();
    }
    
    public function civilite(){

        return DB::table('civilite')
        ->where('id',$this->Civilite)
        ->first();
    }
}
