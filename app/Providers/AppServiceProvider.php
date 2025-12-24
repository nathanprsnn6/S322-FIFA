<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Variante_produit;
use App\Models\Contenir;
use App\Models\Panier;
use App\Http\Controllers\PanierController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            
            $panierController = new PanierController();            
           
            $cartData = $panierController->getCartItems();

            $panierImage = 
            
            $view->with([
                'contenirs' => $cartData['contenirs'],
                'totalPanier' => $cartData['prixpanier']
            ]);
        });
    }
}


