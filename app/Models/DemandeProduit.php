<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeProduit extends Model
{
    protected $table = "demandeProduit";
    protected $primaryKey = "idDemandeProduit";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'idpersonne',
        'sujet',
        'message'
    ];
}