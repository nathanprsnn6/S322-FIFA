@extends('layouts.app')

@section('content') 
    <section id="p2" class="container">
        <div class="dots">
            <div class="dot"></div>
            <div class="dotN"></div>
            <div class="dot"></div>
        </div>
        <p class="header-top">
            Vous avez déjà un compte ? <a href="login">&nbsp; Se connecter</a>
        </p>

        @if ($errors->any())
            <div style="color: red; background: #ffcccc; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>S'inscrire</h1>
        <h2>Étape 2 sur 3<br><b>FIFA</b></h2>

        <form action="{{ route('inscription2.store') }}" method="POST">
            @csrf {{-- Token de sécurité obligatoire --}}

            <div class="form-group">
                <label for="nickname">Surnom *</label>
                <p class="hint-text">Les autres utilisateurs auront accès à cette information</p>
                <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}" required>
            </div>

            <p class="form-group">
                <label for="favorite">Favori</label>
                <select id="favorite" name="favorite" class="form-control">
                    <option value="">-- Choisir une nation --</option>
                    @foreach($nations as $nation)
                    <option value="{{ $nation->idnation }}" {{ old('favorite') == $nation->idnation ? 'selected' : '' }}>
                        {{ $nation->nomnation }}
                    </option>
                    @endforeach
                </select>
            </p>

            <p class="hint-text">Pour en savoir plus, rendez-vous sur <a href="#"> le portail de protection des données de la FIFA</a> </p>
            

            <button type="submit" class="button-primary btnP3" id="submitButton2">POURSUIVRE</button>
        </form>
    </section>
@endsection 
@section('scripts')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection