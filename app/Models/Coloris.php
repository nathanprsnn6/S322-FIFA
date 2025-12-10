<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coloris extends Model
{
    protected $table = "coloris";
    protected $primaryKey = "idcoloris";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'libellecoloris'
    ];
}