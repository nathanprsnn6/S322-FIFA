<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variante_produit extends Model
{
    protected $table = "variante_produit";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'idproduit',
        'idcoloris',
        'prixproduit'
    ];
}