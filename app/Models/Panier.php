<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Panier extends Model
{
    protected $table = "panier";
    public $timestamps = false;
    protected $primarykey = "idpanier";
    protected $fillable = [
        'idpanier',
        'prixpanier',
        'datecreationpanier'
    ];
}
