@extends('layouts.app')

@section('content')
    <div class="app-container">
        <header>
            <div class="header-top">
                <h1>Service Expédition</h1>
                <div class="user-profile">
                    <span>Connecté : <strong>Pierre (Logistique)</strong></span>
                </div>
            </div>
            <div class="filters-container">
                <form action="{{ route('expedition.index') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="date">Date de livraison :</label>
                        <input type="date" id="date" name="date" value="{{ $currentDate }}" onchange="this.form.submit()">
                    </div>
                    <div class="form-group">
                        <label for="slot">Créneau :</label>
                        <select id="slot" name="slot" onchange="this.form.submit()">
                            <option value="all" {{ $currentSlot == 'all' ? 'selected' : '' }}>Toute la journée</option>
                            <option value="Matin" {{ $currentSlot == 'Matin' ? 'selected' : '' }}>Matin</option>
                            <option value="Après-midi" {{ $currentSlot == 'Après-midi' ? 'selected' : '' }}>Après-midi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transport">Transport :</label>
                        <select id="transport" name="transport" onchange="this.form.submit()">
                            <option value="all" {{ $currentTransport == 'all' ? 'selected' : '' }}>Tous les transports</option>
                            @foreach($transportTypes as $type)
                                <option value="{{ $type->idtypelivraison }}" {{ $currentTransport == $type->idtypelivraison ? 'selected' : '' }}>
                                    {{ $type->libelletypelivraison }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-filter">Actualiser</button>
                        <a href="{{ route('expedition.index') }}" class="btn-reset">Aujourd'hui</a>
                    </div>
                </form>
            </div>
        </header>
        <main>
            <div class="table-card">
                <div class="card-header">
                    <h2>Liste des expéditions</h2>
                    <span class="badge-count">{{ count($orders) }} commandes</span>
                </div>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>CMD #</th>
                            <th>Client</th>
                            <th>Adresse / Ville</th>
                            <th>Type Transport</th>
                            <th>Date</th>
                            <th>Créneau</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->idcommande }}</strong></td>
                            <td>{{ $order->nomcomplet }}</td>
                            <td>
                                {{ $order->ruelivraison }}<br>
                                <small>{{ $order->cplivraison }} {{ $order->villelivraison }}</small>
                            </td>
                            <td>
                                <span class="badge badge-blue">
                                    {{ $order->libelletypelivraison }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->datelivraison)->format('d/m/Y') }}</td>
                            <td>
                                <span class="slot-tag {{ $order->creneaulivraison == 'Matin' ? 'slot-matin' : 'slot-pm' }}">
                                    {{ $order->creneaulivraison }}
                                </span>
                            </td>
                            <td>{{ $order->etatcommande }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-row">Aucune commande trouvée pour cette sélection.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection 