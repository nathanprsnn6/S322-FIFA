@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1000px; margin-top: 50px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: #64748b; font-weight: 800; text-transform: uppercase;">
            <i class="fas fa-eye-slash"></i> Produits en attente de publication
        </h1>
        <a href="{{ route('vente.create') }}" style="color: #64748b; text-decoration: none; font-weight: bold;">
            <i class="fas fa-arrow-left"></i> Retour Création
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($produits->isEmpty())
        <div style="background-color: #f1f5f9; color: #64748b; padding: 30px; border-radius: 8px; text-align: center; border: 2px dashed #cbd5e1;">
            <i class="fas fa-check-circle" style="font-size: 2em; margin-bottom: 10px; display: block;"></i>
            Aucun produit en attente. Tout est visible sur le site !
        </div>
    @else
        <div style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 15px; text-align: left; width: 80px;">Image</th>
                        <th style="padding: 15px; text-align: left;">Produit</th>
                        <th style="padding: 15px; text-align: center;">État</th>
                        <th style="padding: 15px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produits as $prod)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                @if($prod->destinationphoto)
                                    <img src="{{ asset($prod->destinationphoto) }}" style="width: 50px; height: 50px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px;">
                                @else
                                    <div style="width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center; color: #aaa; border-radius: 4px;">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: bold; font-size: 1.1em;">{{ $prod->titreproduit }}</div>
                                <div style="font-size: 0.9em; color: #666;">Ref: #{{ $prod->idproduit }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #f1f5f9; color: #64748b; padding: 5px 10px; border-radius: 20px; font-size: 0.85em; font-weight:bold;">
                                    <i class="fas fa-lock"></i> Invisible
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    
                                    {{-- Bouton PUBLIER direct --}}
                                    <form action="{{ route('vente.publier', $prod->idproduit) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" style="background-color: #16a34a; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 0.9em; display: flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-globe"></i> PUBLIER
                                        </button>
                                    </form>

                                    {{-- Lien pour modifier si besoin --}}
                                    <a href="{{ route('vente.edit', $prod->idproduit) }}" title="Vérifier avant de publier"
                                       style="background-color: #e2e8f0; color: #475569; padding: 8px 12px; text-decoration: none; border-radius: 5px; font-size: 0.9em;">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection