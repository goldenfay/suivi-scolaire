<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class ParentEleve extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table="parent";
    // protected $guard="parent";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Code',
        'Nom',
        'Prenom',
        'Civilite',
        'Email',
        'Adresse',
        'Age',
        'NumTel',
        'Etat',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
       
    ];

    public function routeNotificationForMail(){
        return $this->Email;
    }

    public function enfants(){

        return DB::table('eleve_parent')
        ->leftjoin('eleve as E','Eleve','E.id')
        ->where('Parent',$this->id)
        ->get();

    }
    public function civilite(){

        return DB::table('civilite')
        ->where('id',$this->Civilite)
        ->first();
    }

   
    
}
