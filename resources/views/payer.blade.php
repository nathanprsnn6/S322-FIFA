@extends('layouts.app')
@section('content')
    <h3>Régler la commande #{{ $idcommande }}</h3>

    <p>Montant total à payer : <strong>{{ number_format($montant, 2) }} €</strong></p>

    <form method="POST" action="{{ route('payer.effectuer') }}">
        @csrf
        <input type="hidden" name="idcommande" value="{{ $idcommande }}">

        <div class="form-group">
            <label for="card_number">Numéro de Carte</label>
            <input type="text" id="cb" name="cb" required class="form-control">
        </div>

        <div class="form-group">
            <label for="expiry_date">Date d'expiration</label>
            <input type="text" id="exp_date" name="exp_date" required class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-3">Confirmer le Paiement</button>
    </form>
@endsection

