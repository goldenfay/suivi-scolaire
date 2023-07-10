<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Prof extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "professeur";
    protected $guard = "prof";
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
        'Diplome',
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function routeNotificationForMail()
    {
        return $this->Email;
    }

    public function civilite()
    {

        return DB::table('civilite')
            ->where('id', $this->Civilite)
            ->first();
    }

    public function classes()
    {

        return DB::table('professeur_classe')
            ->where('Professeur', $this->id)
            ->leftjoin('classe as c', 'Classe', 'c.id')
            ->select('c.*')
            ->get()
            ->unique();
    }

    public function channels()
    {
        return DB::table('class_prof_telegram')
            ->where('prof_id', $this->id)->get();
    }
}
