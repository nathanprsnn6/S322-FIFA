<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAction extends Model
{
    protected $table = "typeaction";
    protected $primaryKey = "idtypeaction";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'libelletypeaction'
    ];
}