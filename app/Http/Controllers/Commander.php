<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Commande;

class Commander extends Controller{
    public function index()
    {
        $commanders = Commande::all();
        
        return view('commander', compact('commanders'));
    }
}