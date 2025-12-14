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
            <table class="table-cards">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Livraison</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandesEnCours as $commande)
                        <tr class="card-row" style="border-left: 5px solid #034f96;">
                            <td style="font-weight: bold; color: #333;">#{{ $commande->idcommande }}</td>
                            <td>{{ $commande->datetransaction ? \Carbon\Carbon::parse($commande->datetransaction)->format('d/m/Y') : '-' }}</td>
                            <td style="font-weight: 800; font-size: 1.1em;">{{ number_format($commande->prixpanier, 2, ',', ' ') }} €</td>
                            <td>{{ $commande->libelletypelivraison ?? 'Standard' }}</td>
                            <td>
                                <span class="badge badge-blue">{{ $commande->etatcommande }}</span>
                            </td>
                            <td>
                                <button onclick="toggleDetails({{ $commande->idcommande }})" class="btn-detail">
                                    Voir les articles
                                </button>
                            </td>
                        </tr>

                        <tr id="details-{{ $commande->idcommande }}" class="detail-row">
                            <td colspan="6">
                                <div class="detail-content">
                                    <h5>CONTENU DU COLIS</h5>
                                    <ul>
                                        @foreach($commande->produits as $produit)
                                            <li>
                                                <div style="display: flex; flex-direction: column;">
                                                    <span class="prod-name">{{ $produit->titreproduit }}</span>
                                                    <span style="font-size: 0.85em; color: #888; margin-top: 2px;">
                                                        Taille : {{ $produit->tailleproduit }} | Couleur : {{ $produit->libellecoloris }}
                                                    </span>
                                                </div>
                                                <span class="prod-qty">x {{ $produit->qteproduit }}</span>
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

    <div>
        <h2 style="color: #444; font-weight: 700; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;">
            Historique des commandes
        </h2>

        @if($commandesPassees->isEmpty())
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px;">
                <p style="color: #666;">Aucun historique de commande.</p>
            </div>
        @else
            <table class="table-cards">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandesPassees as $commande)
                        @php
                            $borderColor = '#ccc';
                            $badgeClass = 'badge-gray';
                            
                            if($commande->etatcommande == 'Livrée') { 
                                $borderColor = '#28a745'; 
                                $badgeClass = 'badge-green';
                            } elseif ($commande->etatcommande == 'Annulée') { 
                                $borderColor = '#dc3545'; 
                                $badgeClass = 'badge-red';
                            }
                        @endphp

                        <tr class="card-row" style="border-left: 5px solid {{ $borderColor }};">
                            <td style="font-weight: bold; color: #555;">#{{ $commande->idcommande }}</td>
                            <td>{{ $commande->datetransaction ? \Carbon\Carbon::parse($commande->datetransaction)->format('d/m/Y') : '-' }}</td>
                            <td style="font-weight: 800; font-size: 1.1em;">{{ number_format($commande->prixpanier, 2, ',', ' ') }} €</td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $commande->etatcommande }}</span>
                            </td>
                            <td>
                                <button onclick="toggleDetails({{ $commande->idcommande }})" class="btn-detail-outline">
                                    Voir le détail
                                </button>
                            </td>
                        </tr>

                        <tr id="details-{{ $commande->idcommande }}" class="detail-row">
                            <td colspan="5">
                                <div class="detail-content">
                                    <h5>PRODUITS ACHETÉS</h5>
                                    <ul>
                                        @foreach($commande->produits as $produit)
                                            <li>
                                                <div style="display: flex; flex-direction: column;">
                                                    <span class="prod-name">{{ $produit->titreproduit }}</span>
                                                    <span style="font-size: 0.85em; color: #888; margin-top: 2px;">
                                                        Taille : {{ $produit->tailleproduit }} | Couleur : {{ $produit->libellecoloris }}
                                                    </span>
                                                </div>
                                                <span class="prod-qty">x {{ $produit->qteproduit }}</span>
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

<script>
    function toggleDetails(id) {
        var row = document.getElementById('details-' + id);
        if (row.style.display === 'none' || row.style.display === '') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    }
</script>

@endsection