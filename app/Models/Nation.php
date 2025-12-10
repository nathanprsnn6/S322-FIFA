<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    protected $table = "nation";
    protected $primaryKey = "idnation";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'nomnation'
    ];
}