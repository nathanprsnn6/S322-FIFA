<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = "categorie";
    protected $primaryKey = "idcategorie";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'nomcategorie'
    ];
}