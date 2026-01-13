<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model {
    protected $table = 'blog';
    protected $primaryKey = 'idpublication';

    protected $fillable = ['textarticle'];
    public $incrementing = false; // Car l'ID vient de Publication
    public $timestamps = false;

    // Un blog appartient à un Article (et donc une Publication)
public function publication() {
    return $this->belongsTo(Publication::class, 'idpublication', 'idpublication');
}

    // Un blog a plusieurs commentaires
public function commentaires() {
        return $this->hasMany(Commentaire::class, 'idpublication', 'idpublication');
    }
}