<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeVote extends Model
{
    // Nom de la table (si différente de type_votes)
    protected $table = 'typevote';

    // Définition de la clé primaire personnalisée
    protected $primaryKey = 'idtypevote';
    public $incrementing = true; 
    protected $keyType = 'int';

    // Autoriser l'assignation de masse
    protected $fillable = ['nomtypevote','datefin'];

    // Si vous n'avez pas de colonnes created_at et updated_at, décommentez la ligne suivante :
    public $timestamps = false;
    public function joueurs()
{
    // Un type de vote "appartient à plusieurs" joueurs via la table pivot
    return $this->belongsToMany(Joueur::class, 'eligiblevote', 'idtypevote', 'idpersonne');
}
}