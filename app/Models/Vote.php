<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $table = "voter";
    protected $primaryKey = "uti_idpersonne";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['uti_idpersonne', 'idpersonne', 'position','idtypevote'];
    public function personne()
    {
        // belongsTo(ModeleCible, MaCléEtrangère, CléCible)
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }
    public function utilisateur()
    {
        // belongsTo(ModeleCible, MaCléEtrangère, CléCible)
        return $this->belongsTo(Utilisateur::class, 'idpersonne', 'uti_idpersonne');
    }
}