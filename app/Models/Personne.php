<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    use HasFactory;

    protected $table = "personne";

    protected $primaryKey = "idpersonne";

    public $timestamps = false;

    protected $fillable = [
        'lieunaissance',
        'nom',
        'prenom',
        'datenaissance'
    ];
    protected static function booted()
    {
        static::creating(function ($personne) {

            if (empty($personne->idpersonne)) {

                $lastId = static::max('idpersonne');

                $personne->idpersonne = $lastId ? $lastId + 1 : 1;
            }
        });
    }

}