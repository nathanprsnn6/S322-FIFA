<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professionel extends Model
{
    protected $table = "profesionnel";
    protected $primaryKey = "idpersonne";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'idpersonne',
        'tva',
        'nomsociete',
        'activite'
    ];
}