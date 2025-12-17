<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistiques extends Model
{
    protected $table = 'statistiques';

    protected $primaryKey = 'id_statistique';

    public $timestamps = false; 

    protected $fillable = [
        'matchs_joues',
        'titularisations',
        'minutes_jouees',
        'buts',
        'nb_selections',
    ];
}