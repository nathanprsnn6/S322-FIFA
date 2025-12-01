<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = "produit";
    protected $primaryKey = "idproduit";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'descriptionproduit',
        'titreproduit'
    ];
}