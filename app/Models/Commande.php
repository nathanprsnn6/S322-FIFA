<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table ="commande";
    protected $primarykey = "idcommande";
    public $timestamps = false;
    protected $filable = [
        'idcommande',
        'idpanier',
        'idtransaction',
        'idpersonne',
        'etatcommande'
    ];
}