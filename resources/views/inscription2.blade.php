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
        <p class="header-top">
        Les champs marqués d'un &nbsp;*&nbsp; sont obligatoires<br><br>
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
            <label for="courriel">
        Surnom <span class="etoile">*</span>
        <span class="tooltip-container" 
              role="button"
              aria-haspopup="true"
              aria-expanded="false"
              tabindex="0" 
              aria-label="Plus d'informations sur le courriel">
            
            <span class="info-icon" aria-hidden="true">i</span>
            
            <span class="tooltip-box" 
                  id="desc-courriel" 
                  role="tooltip">
                  Votre surnom est public. Il doit être unique : s'il est déjà utilisé par un autre membre, il sera refusé lors de la validation.
            </span>
        </span>
    </label>
                <p class="hint-text">Les autres utilisateurs auront accès à cette information</p>
                <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}" required placeholder="Ex: Coco">
            </div>

            <p class="form-group">
            <label for="courriel">
        Label
        <span class="tooltip-container" 
              role="button"
              aria-haspopup="true"
              aria-expanded="false"
              tabindex="0" 
              aria-label="Plus d'informations sur le courriel">
            
            <span class="info-icon" aria-hidden="true">i</span>
            
            <span class="tooltip-box" 
                  id="desc-courriel" 
                  role="tooltip">
                  Sélectionnez votre équipe nationale favorite. Cette information apparaîtra sur votre profil public.
            </span>
        </span>
    </label>
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