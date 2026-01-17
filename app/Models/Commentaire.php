<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $table = 'commentaire';
    // On précise que la clé primaire n'est pas "id" si nécessaire
    protected $primaryKey = 'idcommentaire'; 

    // LA LIGNE À AJOUTER :
    public $timestamps = false;

    protected $fillable = [
        'idpublication', 
        'idpersonne', 
        'textecommentaire'
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne');
    }
}