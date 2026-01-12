<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Panier extends Model
{
    protected $table = "panier";
    public $timestamps = false;
    protected $primaryKey = "idpanier";
    protected $fillable = [
        'idpanier',
        'idpersonne',        
        'prixpanier',
        'datecreationpanier'
    ];

    public function commande()
    {
        return $this->hasOne(Commande::class, 'idpanier'); 
    }
}
