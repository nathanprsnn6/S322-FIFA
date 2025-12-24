<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produit;
use App\Models\Variante_produit;

class Contenir extends Model
{
    protected $table = "contenir";
    public $timestamps = false;
    protected $primarykey = "idpanier"; 
    protected $fillable = [
        'idpanier',
        'idproduit',
        'idcoloris',
        'idtaille',
        'ligneproduit',
        'qteproduit'
    ];
    
    public function produit()
    {        
        return $this->belongsTo(Produit::class, 'idproduit');
    }

    public function variante()
    {
        return $this->hasOne(Variante_produit::class, 'idproduit', 'idproduit');
    }

    public function getPrixUnitaireAttribute()
    {
        $variante = Variante_produit::where('idproduit', $this->idproduit)
                                    ->where('idcoloris', $this->idcoloris)                                    
                                    ->first();

        if ($variante) {
            return $variante->prixproduit;
        }
        return 0;
    }

}