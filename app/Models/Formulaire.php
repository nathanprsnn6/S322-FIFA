<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulaire extends Model
{
    protected $table = "formulaire";
    protected $primaryKey = "idformulaire";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'idtypeaction',
        'idpersonne',
        'contenuformulaire'
    ];
}