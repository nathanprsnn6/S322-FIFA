<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    protected $table = 'sous_categorie';
    protected $primaryKey = 'idsouscategorie';
    public $timestamps = false;

    protected $fillable = ['idcategorie', 'nomsouscategorie'];
}