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

        <button type="submit" class="button-primary">SE CONNECTER</button>
        <div class="separator">
            <span>OU</span>
        </div>

        <a href="{{ route('google.login') }}" class="btn-google-integrated">
            <svg class="google-svg" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
            </svg>
            <span>Continuer avec Google</span>
        </a>
    </form>
</section>
@endsection