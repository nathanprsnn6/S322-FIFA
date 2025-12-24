<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = "photo";
    protected $primaryKey = "idphoto";
    public $timestamps = false;
    // La colonne 'destinationphoto' est nécessaire pour le blade
}