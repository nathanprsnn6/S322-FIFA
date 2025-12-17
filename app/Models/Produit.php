<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Illustrer; 

class Produit extends Model
{
    protected $table = "produit";
    protected $primaryKey = "idproduit";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'descriptionproduit',
        'idnation',
        'idcategorie',
        'idcompetition',
        'titreproduit'
        //'idphoto'
    ];


    public function illustrer() {
        return $this->hasMany(Illustrer::class, 'idproduit', 'idproduit'); 
    }
    
    public function photo()
    {
        return $this->hasOneThrough(
            Photo::class,
            Illustrer::class,
            'idproduit',
            'idphoto',
            'idproduit',
            'idphoto'
        );
    }
}