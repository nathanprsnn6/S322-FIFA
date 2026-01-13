@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Header dynamique --}}
            <div class="fifa-header-container mb-4">
                <h1 class="fifa-title">
                    {{ isset($type) ? 'Modifier le Type' : 'Ajouter un nouveau Type' }}
                </h1>
            </div>

            <div class="card card-fifa shadow-sm">
                <div class="card-body p-4">
                    {{-- L'action change selon si $type existe (Update) ou non (Store) --}}
                    <form action="{{ isset($type) ? route('typesvote.update', $type->idtypevote) : route('typesvote.store') }}" method="POST">
                        @csrf
                        @if(isset($type))
                            @method('PUT')
                        @endif

                        {{-- Champ Nom --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small" style="color: #001d4d;">Nom du Type de Vote</label>
                            <input type="text" name="nomtypevote" class="form-control fifa-input" 
                                   value="{{ old('nomtypevote', $type->nomtypevote ?? '') }}" 
                                   placeholder="Ex: MEILLEUR BUTEUR" required>
                        </div>

                        {{-- Champ Date de Fin --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small" style="color: #001d4d;">Date Limite de Vote</label>
                            <input type="date" name="datefin" class="form-control fifa-input" 
                                   value="{{ old('datefin', isset($type->datefin) ? \Carbon\Carbon::parse($type->datefin)->format('Y-m-d') : '') }}" 
                                   required>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('typesvote.index') }}" class="text-decoration-none fw-bold text-muted small text-uppercase">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                            </a>
                            <button type="submit" class="btn btn-fifa-primary px-5">
                                {{ isset($type) ? 'Mettre à jour' : 'Enregistrer le type' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection