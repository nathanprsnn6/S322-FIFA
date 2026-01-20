<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "client";
    protected $primaryKey = "idpersonne";
    public $timestamps = false;
    protected $fillable = [
        'cpLivraison',
        'villeLivraison',
        'telephone',
        'paysLivraison',
        'nomcomplet',
        'rueLivraison'
    ];

    public function __toString(): string
    {
        return "Nom client : " . $this->nomcomplet . " - Ville : " . $this->villeLivraison;
    }
}