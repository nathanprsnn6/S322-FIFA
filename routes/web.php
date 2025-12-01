<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonneTest;
use App\Http\Controllers\UtilisateurTest;
use App\Http\Controllers\ProduitTest;
use App\Http\Controllers\InscriptionConnexion;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/personnes', [PersonneTest::class, 'index']);
Route::get('/utilisateurs', [UtilisateurTest::class, 'index']);
Route::get('/produits', [ProduitTest::class, 'index']);
Route::get('/inscriptionConnexion', [InscriptionConnexion::class, 'index']);