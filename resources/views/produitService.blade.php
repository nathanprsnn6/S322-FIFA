@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/fifa-admin.css') }}">

<div class="fifa-container">
    <div class="container">
        <div class="card fifa-card">
            <div class="card-header fifa-card-header">
                <i class="fas fa-file-invoice-dollar fa-2x"></i>
                <h3 class="mb-0">Produits en attente de prix</h3>
            </div>
            
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-fifa m-3">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table fifa-table">
                        <thead>
                            <tr>
                                <th>ID Produit</th>
                                <th>Désignation du Produit</th>
                                <th>Couleur</th>
                                <th class="text-right">Action / Prix (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produits as $produit)
                                <tr>
                                    <td class="align-middle font-weight-bold">#{{ $produit->idproduit }}</td>
                                    <td class="align-middle">{{ $produit->titreproduit }}</td>
                                    <td class="align-middle">{{ $produit->libellecoloris }}</td>
                                    <td class="text-right">
                                        <form action="{{ route('produits.save_prix') }}" method="POST" class="d-inline-flex">
                                            @csrf
                                            <input type="hidden" name="idproduit" value="{{ $produit->idproduit }}">
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="prix" 
                                                       class="fifa-input-prix" 
                                                       placeholder="0.00" required>
                                                <div class="input-group-append ml-2">
                                                    <button type="submit" class="fifa-btn-save">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <p class="h5">Tous les prix sont à jour pour le catalogue FIFA.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection