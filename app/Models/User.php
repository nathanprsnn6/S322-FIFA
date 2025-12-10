<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 1. Nom de la table
    protected $table = 'utilisateur';

    // 2. Clé primaire (Correction "nn")
    protected $primaryKey = 'idpersonne';

    // 3. Pas de created_at/updated_at
    public $timestamps = false;

    // 4. Champs remplissables
    protected $fillable = [
        'idpersonne',
        'nom',
        'prenom',
        'courriel',
        'mdp',
        // 'remember_token' // On ne le met PAS ici
    ];

    protected $hidden = [
        'mdp',
        // 'remember_token', // On retire ça aussi
    ];

    // 5. Gestion du mot de passe 'mdp'
    public function getAuthPassword()
    {
        return $this->mdp;
    }

    public function setMdpAttribute($value)
    {
        $this->attributes['mdp'] = bcrypt($value);
    }

    // --- BLOC MAGIQUE POUR EVITER L'ERREUR REMEMBER_TOKEN ---
    
    // Indique à Laravel que le nom de la colonne est vide
    public function getRememberTokenName()
    {
        return ''; 
    }

    // Ne renvoie rien quand Laravel demande le token
    public function getRememberToken()
    {
        return null;
    }

    // Ne fait RIEN quand Laravel essaie d'écrire dans la BDD
    public function setRememberToken($value)
    {
        // On laisse vide pour empêcher la requête SQL "UPDATE..."
    }
    public function personne()
{
    // On suppose que tu as un modèle Personne, sinon il faut utiliser DB::table
    // belongsTo(ModeleCible, MaCléEtrangère, CléPrimaireCible)
    return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
}
    
    // --- FIN BLOC MAGIQUE ---
}