@extends('layouts.app')

@section('content')
<section id="login-page" class="container">
    <p class="header-top">
        Pas encore de compte ? <a href="inscription1">S'inscrire</a>
    </p>

    <div style="text-align: center; margin-bottom: 30px;">
        <h1>Connexion</h1>
    </div>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        {{-- COURRIEL --}}
        <div class="form-group">
            <label for="courriel">Courriel</label>
            <input type="email" id="courriel" name="courriel" value="{{ old('courriel') }}" required autofocus>
            @error('courriel')
                <span style="color: red; font-size: 0.8em;">{{ $message }}</span>
            @enderror
        </div>

        {{-- MOT DE PASSE (MDP) --}}
        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" required>
        </div>

        <div style="margin-bottom: 20px;">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" style="display:inline; font-weight:normal;">Se souvenir de moi</label>
        </div>

        <button type="submit" class="button-primary">SE CONNECTER</button>
    </form>
</section>
@endsection