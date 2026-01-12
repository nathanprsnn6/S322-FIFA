<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CarteBancaire extends Model
{
    protected $table ="cartebancaire";
    protected $primaryKey = "idcb";
    public $timestamps = false;
    protected $fillable = [
        'idpersonne',
        'refcb',
        'dateexpirationcb',
        'nomcb'
    ];

    public function getRefcbAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value; 
        }
    }

    public function setRefcbAttribute($value)
    {
        $this->attributes['refcb'] = Crypt::encryptString($value);
    }
}