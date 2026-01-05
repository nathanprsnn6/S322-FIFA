<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteBancaire extends Model
{
    protected $table ="cartebancaire";
    protected $primarykey = "idcb";
    public $timestamps = false;
    protected $fillable = [
        'idpersonne',
        'refcb',
        'dateexpirationcb',
        'ccvcb',
        'nomcb'
    ];
}