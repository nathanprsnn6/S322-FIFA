<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Photo;
use App\Models\Article;
use App\Models\Blog;

class Publication extends Model
{
    protected $table = 'publication';
    protected $primaryKey = 'idpublication';
    public $timestamps = false;

    protected $fillable = ['idphoto',
    'datepublication',
    'titrepublication',
    'resumepublication'];

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphoto');
    }
public function blog() {
    // On lie idpublication de 'publication' à idpublication de 'blog'
    return $this->hasOne(Blog::class, 'idpublication', 'idpublication');
}

public function article() {
    return $this->hasOne(Article::class, 'idpublication', 'idpublication');
}
}