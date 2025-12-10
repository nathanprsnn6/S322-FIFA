<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Contenir extends Model
{
    protected $table = "contenir";
    public $timestamps = false;
    protected $primarykey = "idpanier";
    protected $fillable = [
        'idpanier',
        'idproduit',
        'idcommande',
        'ligneproduit',
        'qteproduit'
    ];
}
