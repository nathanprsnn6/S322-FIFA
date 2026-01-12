<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table ="commande";
    protected $primaryKey = "idcommande";
    public $timestamps = false;
    protected $fillable = [
        'idcommande',
        'idpanier',
        'idtransaction',
        'idpersonne',
        'etatcommande'
    ];
}