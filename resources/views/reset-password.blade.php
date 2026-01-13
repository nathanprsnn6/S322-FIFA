@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Nouveau mot de passe</h2>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="courriel" value="{{ $courriel }}">

            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="password" required autofocus placeholder="********">
            </div>

            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required placeholder="********">
            </div>

            <button type="submit" class="btn-primary">
                Mettre à jour mon compte FIFA
            </button>
        </form>

        @error('password')
            <div class="alert-error">{{ $message }}</div>
        @enderror
    </div>
</div>
@endsection