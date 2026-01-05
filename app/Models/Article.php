<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = "article";
    protected $primaryKey = "idpublication";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idpublication',
        'textarticle'
    ];
    public function publication()
    {
        // belongsTo(ModeleCible, MaCléEtrangère, CléCible)
        return $this->belongsTo(Publication::class, 'idpublication', 'idpublication');
    }
    
}
