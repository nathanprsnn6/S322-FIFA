<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'utilisateur';

    protected $primaryKey = 'idpersonne';

    public $timestamps = false;

    protected $fillable = [
        'idpersonne',
        'nom',
        'prenom',
        'courriel',
        'mdp',
        'google_id'
    ];

    protected $hidden = [
        'mdp',
    ];

    public function getAuthPassword()
    {
        return $this->mdp;
    }

    public function setMdpAttribute($value)
    {
        $this->attributes['mdp'] = bcrypt($value);
    }

    public function getRememberTokenName()
    {
        return ''; 
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
    }
    public function personne()
{

    return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
}

}