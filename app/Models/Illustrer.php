<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Photo;


class Illustrer extends Model
{
    protected $table = "illustrer"; 
    protected $primaryKey = "idproduit";
    public $timestamps = false;
    
    protected $fillable = [
        'idphoto' 
    ];
    
    // Si besoin d'accéder au produit depuis la photo, vous ajouteriez :
    /*
    public function produit()
    {
        return $this->hasMany(Produit::class, 'idphoto', 'idphoto');
    }  */
    
}