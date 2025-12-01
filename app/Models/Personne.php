<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    use HasFactory;

    // 1. Définition de la table
    protected $table = "personne";

    // 2. Correction : C'est 'primaryKey' avec un K majuscule
    protected $primaryKey = "idpersonne";

    // 3. Désactive les colonnes created_at / updated_at
    public $timestamps = false;

    // 4. Correction : C'est 'fillable' avec deux 'l'
    // Liste des colonnes modifiables
    protected $fillable = [
        'lieunaissance',
        'nom',
        'prenom',
        'datenaissance'
    ];

}