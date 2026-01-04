<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}