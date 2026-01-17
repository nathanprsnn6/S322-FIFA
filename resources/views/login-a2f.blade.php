@extends('layouts.app')

@section('content')
<section id="login-page" class="container">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1>Vérification de sécurité</h1>
        <p>Un code de validation a été envoyé par SMS.</p>
    </div>

    <form method="POST" action="{{ route('login.a2f.verify') }}">
        @csrf
        <div class="form-group">
            <!-- <p>{{ session('a2f_code') }}</p> -->
            <label for="code">Code reçu par SMS</label>
            <input type="text" id="code" name="code" placeholder="123456" required autofocus 
                   style="text-align: center; font-size: 2rem; letter-spacing: 5px;">
            @error('code')
                <span style="color: red; font-size: 0.8em;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="button-primary">VALIDER LE CODE</button>
        
        <div class="forgot-link-container">
            <a href="{{ route('login') }}" class="link-blue">Retour à la connexion</a>
        </div>
    </form>
</section>
@endsection