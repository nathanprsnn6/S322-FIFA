<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistiques extends Model
{
    // Nom exact de ta table dans la BDD
    protected $table = 'statistiques';

    // Ta clé primaire personnalisée
    protected $primaryKey = 'id_statistique';

    // Désactiver les timestamps si tu n'as pas les colonnes created_at et updated_at
    public $timestamps = false; 

    // Les champs qu'on autorise à modifier
    protected $fillable = [
        'matchs_joues',
        'titularisations',
        'minutes_jouees',
        'buts',
        'nb_selections',
        // 'saison' et 'idjoueur' ne changent pas lors de la mise à jour
    ];
}