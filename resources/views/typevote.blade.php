@extends('layouts.app')

@section('content')
<div class="container mt-5">
    {{-- Header : Titre à gauche, Bouton à droite --}}
    <div class="fifa-header-container d-flex justify-content-between align-items-center mb-5">
        <h1 class="fifa-title m-0">Gestion des Types de Votes</h1>
        <div class="fifa-actions">
            {{-- Lien vers la page de création --}}
            <a href="{{ route('typesvote.create') }}" class="btn btn-fifa-primary shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Ajouter un type
            </a>
        </div>
    </div>

    <div class="card card-fifa">
        <div class="card-body p-0"> 
            <table class="table table-fifa table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nom du Type de Vote</th>
                        <th>Date Limite</th>
                        <th class="text-end pe-4">modification</th>
                        <th class="text-end pe-4">Joueurs</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($typesVotes as $type)
                        <tr>
                            <td class="ps-4 text-muted">#{{ $type->idtypevote }}</td>
                            <td class="fw-bold text-uppercase">{{ $type->nomtypevote }}</td>
                            <td>
                                <span class="badge-date">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $type->datefin ? \Carbon\Carbon::parse($type->datefin)->format('d/m/Y') : 'Pas de date' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                {{-- Lien vers la page de modification avec l'ID --}}
                                <a href="{{ route('typesvote.edit', $type->idtypevote) }}" class="btn btn-sm btn-outline-primary btn-action">
                                    <i class="fas fa-pen"></i> Modifier
                                </a>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('typesvote.joueurs', $type->idtypevote) }}" class="btn btn-sm btn-outline-info btn-action me-2" style="border-color: #00b2ff; color: #00b2ff;">
        <i class="fas fa-users"></i> Joueurs
    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Colspan passé à 4 car tu as 4 colonnes --}}
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle mb-2 d-block fa-2x"></i>
                                Aucun type de vote disponible.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection