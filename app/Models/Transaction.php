<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $table = 'transaction';
    protected $primaryKey = 'idtransaction';
    
    protected $fillable = [
        'idcb', 
        'datetransaction', 
        'montanttransaction'
    ];
    
    public $timestamps = false; 
}