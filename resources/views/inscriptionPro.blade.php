@extends('layouts.app')

@section('content')
<section id="ppro" class="container">
    {{-- Fil d'ariane ou retour --}}
    <p class="header-top">
        <a href="{{ url('/') }}">&larr; Passer pour plus tard</a>
    </p>

    <div>
        <h1>Espace Professionnel</h1>
        <h2><b>Finaliser votre compte entreprise</b></h2>
        <p class="hint-text" style="margin-bottom: 25px;">
            Ces informations sont nécessaires pour la facturation et la validation de votre statut.
        </p>
        
        <form method="post" action="{{ route('pro.store') }}">
            @csrf {{-- OBLIGATOIRE pour la sécurité Laravel --}}

            <div class="form-group">
                <label for="nomsociete">Nom de la société *</label>
                <input type="text" 
                       id="nomsociete" 
                       name="nomsociete" 
                       value="{{ old('nomsociete') }}" 
                       required 
                       placeholder="Ex: FIFA Corp">
                
                @error('nomsociete')
                    <span style="color: red; font-size: 0.85em;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tva">Numéro de TVA *</label>
                <input type="text" 
                       id="tva" 
                       name="tva" 
                       value="{{ old('tva') }}" 
                       required 
                       placeholder="Ex: FR 12 3456789">
                
                @error('tva')
                    <span style="color: red; font-size: 0.85em;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="activite">Activité principale *</label>
                <input type="text" 
                       id="activite" 
                       name="activite" 
                       value="{{ old('activite') }}" 
                       required 
                       placeholder="Ex: Vente d'articles de sport">
                
                @error('activite')
                    <span style="color: red; font-size: 0.85em;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="button-primary" id="submitButtonPro">
                VALIDER MON COMPTE PRO
            </button>           
        </form>          
    </div>
</section>
@endsection 

@section('scripts')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection