<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = "utilisateur";
    protected $primaryKey = "idpersonne";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idpersonne',
        'idnation',
        'idrole',
        'naiss_idnation',
        'courriel',
        'surnom',
        'favori_idnation',
        'langue_idnation',
        'cp',
        'ville',
        'mdp'
    ];
    public function personne()
    {
        // belongsTo(ModeleCible, MaCléEtrangère, CléCible)
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }
}
