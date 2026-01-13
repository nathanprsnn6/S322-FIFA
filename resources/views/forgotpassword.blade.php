@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('inscription1') }}" class="back-link">S'inscrire</a> <h2>Récupération de compte</h2>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            
 <div class="form-group">
    <label for="courriel">Courriel</label>
    <input type="email" name="courriel" id="courriel" placeholder="votre@email.com" required autofocus value="{{ old('courriel') }}">
</div>

@error('courriel')
    <div class="alert-error">{{ $message }}</div>
@enderror

            <button type="submit" class="btn-primary">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        @error('courriel')
            <div class="alert-error">{{ $message }}</div>
        @enderror
    </div>
</div>
@endsection