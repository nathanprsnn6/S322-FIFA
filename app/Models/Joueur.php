<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joueur extends Model
{
    protected $table = "joueur";
    protected $primaryKey = "idpersonne";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idpersonne',
        'idnation',
        'idequipe',
        'poste',
        'piedprefere',
        'poids',
        'taille',
        'biographie',
        'nbselection'
    ];
    public function personne()
    {
        // belongsTo(ModeleCible, MaCléEtrangère, CléCible)
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }
}
