@extends('layouts.app')

@section('content')
    <div class="app-container expedition-scope">
        
        <header>
            <div class="header-top">
                <h1>Service Expédition & Suivi</h1>
                <div class="user-profile">
                    <span>Connecté : <strong>Logistique</strong></span>
                </div>
            </div>

            @if(session('success'))
                <div style="background: #d1fae5; color: #065f46; padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-weight: bold;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="filters-container">
                <form action="{{ route('expedition.index') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="date">Date de livraison :</label>
                        {{-- On laisse la valeur vide par défaut pour tout voir --}}
                        <input type="date" id="date" name="date" value="{{ $currentDate }}" onchange="this.form.submit()">
                        @if($currentDate)
                            <a href="{{ route('expedition.index') }}" style="font-size: 0.8em; color: red;">X Effacer filtre</a>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="transport">Transporteur :</label>
                        <select id="transport" name="transport" onchange="this.form.submit()">
                            <option value="all">Tous</option>
                            @foreach($transportTypes as $type)
                                <option value="{{ $type->idtypelivraison }}" {{ $currentTransport == $type->idtypelivraison ? 'selected' : '' }}>
                                    {{ $type->libelletypelivraison }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </header>

        <main>
            <div class="table-card">
                <div class="card-header">
                    <h2>Gestion des Expéditions</h2>
                    <span class="badge-count">{{ count($orders) }} commandes</span>
                </div>
                
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>CMD #</th>
                            <th>Client</th>
                            <th>Adresse</th>
                            <th>Transport / Date</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td><strong>#{{ $order->idcommande }}</strong></td>
                            <td>{{ $order->nomcomplet }}</td>
                            <td>
                                <small>{{ $order->ruelivraison }}<br>{{ $order->cplivraison }} {{ $order->villelivraison }}</small>
                            </td>
                            <td>
                                {{-- Gestion de l'affichage si pas encore planifié --}}
                                @if($order->libelletypelivraison)
                                    <span class="badge badge-blue">{{ $order->libelletypelivraison }}</span><br>
                                    <small>
                                        {{ \Carbon\Carbon::parse($order->datelivraison)->format('d/m/Y') }} 
                                        ({{ $order->creneaulivraison }})
                                    </small>
                                @else
                                    <span style="color: #999; font-style: italic;">Non planifié</span>
                                @endif
                            </td>
                            <td>
                                @if($order->etatcommande == 'En préparation')
                                    <span style="background:#fff3cd; color:#856404; padding:4px 8px; border-radius:4px; font-weight:bold;">En préparation</span>
                                @elseif($order->etatcommande == 'En cours de livraison')
                                    <span style="background:#d1fae5; color:#065f46; padding:4px 8px; border-radius:4px; font-weight:bold;">En cours</span>
                                @elseif($order->etatcommande == 'Livrée')
                                    <span style="background:#dcfce7; color:#166534; padding:4px 8px; border-radius:4px; font-weight:bold;">Livrée</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                {{-- LOGIQUE DES BOUTONS --}}
                                @if($order->etatcommande == 'En préparation')
                                    <form action="{{ route('expedition.expedier', $order->idcommande) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background-color: #27ae60; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                                            <i class="fas fa-truck"></i> Expédier
                                        </button>
                                    </form>
                                @elseif($order->etatcommande == 'En cours de livraison')
                                    <button disabled style="background-color: #e5e7eb; color: #9ca3af; border: none; padding: 8px 15px; border-radius: 5px; cursor: not-allowed;">
                                        <i class="fas fa-shipping-fast"></i> En transit
                                    </button>
                                @else
                                    <button disabled style="background-color: #f3f4f6; color: #374151; border: 1px solid #ddd; padding: 8px 15px; border-radius: 5px; cursor: not-allowed;">
                                        <i class="fas fa-check"></i> Terminé
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-row" style="text-align: center; padding: 20px; color: #666;">
                                Aucune commande trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection