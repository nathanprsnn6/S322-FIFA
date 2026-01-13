@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 50px; max-width: 1000px;">
    
    <div style="margin-bottom: 60px;">
        <h2 style="color: #034f96; font-weight: 800; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;">
            MES COMMANDES EN COURS
        </h2>

        @if($commandesEnCours->isEmpty())
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px;">
                <p style="color: #666; font-size: 1.1em;">Aucune commande en cours actuellement.</p>
            </div>
        @else
            <table class="table-cards" style="width: 100%; border-collapse: separate; border-spacing: 0 15px;">
                <thead>
                    <tr style="text-align: left; color: #888; text-transform: uppercase; font-size: 0.85em;">
                        <th style="padding: 10px;">N°</th>
                        <th style="padding: 10px;">Date</th>
                        <th style="padding: 10px;">Montant Total</th>
                        <th style="padding: 10px;">Livraison</th>
                        <th style="padding: 10px;">Statut</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandesEnCours as $commande)
                        <tr class="card-row" style="background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #034f96;">
                            <td style="padding: 20px; font-weight: bold; color: #333;">#{{ $commande->idcommande }}</td>
                            <td style="padding: 20px;">{{ $commande->datetransaction ? \Carbon\Carbon::parse($commande->datetransaction)->format('d/m/Y') : '-' }}</td>
                            <td style="padding: 20px; font-weight: 800; font-size: 1.1em;">{{ number_format($commande->montant_total, 2, ',', ' ') }} €</td>
                            <td style="padding: 20px;">{{ $commande->libelletypelivraison ?? 'Standard' }}</td>
                            <td style="padding: 20px;">
                                <span class="badge badge-blue" style="background: #e3f2fd; color: #0d47a1; padding: 5px 10px; border-radius: 20px; font-size: 0.85em; font-weight: bold;">
                                    {{ $commande->etatcommande }}
                                </span>
                            </td>
                            <td style="padding: 20px;">
                                <button class="btn-detail js-toggle-order-details" data-id="{{ $commande->idcommande }}" 
                                        style="background: #034f96; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">
                                    Voir les articles
                                </button>
                            </td>
                        </tr>

                        <tr id="details-{{ $commande->idcommande }}" class="detail-row" style="display: none;">
                            <td colspan="6" style="padding: 0;">
                                <div class="detail-content" style="background-color: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; border: 1px solid #eee; border-top: none; margin-bottom: 20px;">
                                    
                                    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                                        
                                        <div style="flex: 1; min-width: 300px;">
                                            <h5 style="color: #034f96; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">
                                                <i class="fas fa-box-open"></i> CONTENU DU COLIS
                                            </h5>
                                            <ul style="list-style: none; padding: 0;">
                                                @foreach($commande->produits as $produit)
                                                    <li style="margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 10px; display: flex; justify-content: space-between;">
                                                        <div>
                                                            <span style="font-weight: bold; color: #333;">{{ $produit->titreproduit }}</span> <br>
                                                            <span style="font-size: 0.9em; color: #666;">
                                                                Taille : {{ $produit->tailleproduit }} | Couleur : {{ $produit->libellecoloris }}
                                                            </span>
                                                        </div>
                                                        <span style="font-weight: bold; color: #034f96;">x{{ $produit->qteproduit }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <div style="width: 300px; background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; height: fit-content;">
                                            <h5 style="color: #e67e22; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">
                                                <i class="fas fa-truck"></i> SUIVI EXPÉDITION
                                            </h5>
                                            
                                            @if($commande->etatcommande == 'En préparation')
                                                <div style="color: #666; font-style: italic; text-align: center; padding: 10px;">
                                                    <i class="fas fa-box" style="font-size: 2em; color: #ccc; margin-bottom: 10px;"></i><br>
                                                    Votre commande est en cours de préparation à l'entrepôt.
                                                </div>
                                            @elseif(isset($commande->datelivraison)) 
                                                <div style="margin-bottom: 15px;">
                                                    <strong style="color: #555; display: block; font-size: 0.9em;">DATE DE LIVRAISON ESTIMÉE</strong>
                                                    <span style="font-size: 1.4em; color: #034f96; font-weight: bold;">
                                                        {{ \Carbon\Carbon::parse($commande->datelivraison)->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                                
                                                <div style="margin-bottom: 15px;">
                                                    <strong style="color: #555; display: block; font-size: 0.9em;">CRÉNEAU HORAIRE</strong>
                                                    <span style="font-size: 1.1em; color: #333;">
                                                        {{ $commande->creneaulivraison ?? 'Journée (8h - 18h)' }}
                                                    </span>
                                                </div>

                                                <div>
                                                    <strong style="color: #555; display: block; font-size: 0.9em;">MODE DE LIVRAISON</strong>
                                                    <span class="badge badge-blue" style="background: #e3f2fd; color: #0d47a1; padding: 3px 8px; border-radius: 4px; font-size: 0.9em; margin-top: 5px; display: inline-block;">
                                                        {{ $commande->libelletypelivraison }}
                                                    </span>
                                                </div>
                                            @else
                                                <p style="color: #666;">Informations de suivi en attente.</p>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div>
        <h2 style="color: #444; font-weight: 700; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;">
            Historique des commandes
        </h2>

        @if($commandesPassees->isEmpty())
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px;">
                <p style="color: #666;">Aucun historique de commande.</p>
            </div>
        @else
            <table class="table-cards" style="width: 100%; border-collapse: separate; border-spacing: 0 15px;">
                <thead>
                    <tr style="text-align: left; color: #888; text-transform: uppercase; font-size: 0.85em;">
                        <th style="padding: 10px;">N°</th>
                        <th style="padding: 10px;">Date</th>
                        <th style="padding: 10px;">Montant Total</th>
                        <th style="padding: 10px;">Statut</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandesPassees as $commande)
                        @php
                            $borderColor = '#ccc';
                            $badgeStyle = 'background: #e0e0e0; color: #555;';
                            
                            if($commande->etatcommande == 'Livrée') { 
                                $borderColor = '#28a745'; 
                                $badgeStyle = 'background: #d4edda; color: #155724;';
                            } elseif ($commande->etatcommande == 'Annulée') { 
                                $borderColor = '#dc3545'; 
                                $badgeStyle = 'background: #f8d7da; color: #721c24;';
                            }
                        @endphp

                        <tr class="card-row" style="background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid {{ $borderColor }};">
                            <td style="padding: 20px; font-weight: bold; color: #555;">#{{ $commande->idcommande }}</td>
                            <td style="padding: 20px;">{{ $commande->datetransaction ? \Carbon\Carbon::parse($commande->datetransaction)->format('d/m/Y') : '-' }}</td>
                            <td style="padding: 20px; font-weight: 800; font-size: 1.1em;">{{ number_format($commande->montant_total, 2, ',', ' ') }} €</td>
                            <td style="padding: 20px;">
                                <span class="badge" style="{{ $badgeStyle }} padding: 5px 10px; border-radius: 20px; font-size: 0.85em; font-weight: bold;">
                                    {{ $commande->etatcommande }}
                                </span>
                            </td>
                            <td style="padding: 20px;">
                                <button class="btn-detail-outline js-toggle-order-details" data-id="{{ $commande->idcommande }}"
                                        style="background: white; border: 1px solid #ccc; color: #555; padding: 8px 15px; border-radius: 5px; cursor: pointer;">
                                    Voir le détail
                                </button>
                            </td>
                        </tr>

                        <tr id="details-{{ $commande->idcommande }}" class="detail-row" style="display: none;">
                            <td colspan="5" style="padding: 0;">
                                <div class="detail-content" style="background-color: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; border: 1px solid #eee; border-top: none; margin-bottom: 20px;">
                                    <h5>PRODUITS ACHETÉS</h5>
                                    <ul style="list-style: none; padding: 0;">
                                        @foreach($commande->produits as $produit)
                                            <li style="margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 10px; display: flex; justify-content: space-between;">
                                                <div>
                                                    <span style="font-weight: bold; color: #333;">{{ $produit->titreproduit }}</span> <br>
                                                    <span style="font-size: 0.9em; color: #666;">
                                                        Taille : {{ $produit->tailleproduit }} | Couleur : {{ $produit->libellecoloris }}
                                                    </span>
                                                </div>
                                                <span style="font-weight: bold; color: #555;">x{{ $produit->qteproduit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection