@extends('layouts.app')

@section('content')

<div id="joueurs" class="container mx-auto px-4 py-8">
    
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-blue-900 mb-2">Élisez votre Top 3</h1>
        <p class="text-gray-600">Sélectionnez votre <span class="text-yellow-500 font-bold">#1</span>, <span class="text-gray-400 font-bold">#2</span> et <span class="text-orange-500 font-bold">#3</span>.</p>
    </div>

    <form action="#" method="POST" id="voteForm">
        @csrf
        
        <div class="flex flex-wrap justify-center gap-6">
            @forelse($joueurs as $joueur)
                
                <div class="div_joueurs w-72 bg-white rounded-xl shadow-lg overflow-hidden relative group hover:shadow-xl transition-shadow duration-300">
                    
                    <a href="{{ route('voter.show', ['id' => $joueur->idpersonne]) }}" class="block p-0">
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ asset('img/joueur-test.jpg') }}" 
                                 alt="Photo de {{ $joueur->personne->prenom }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>

                        <div class="p-4 text-center">
                            <h2 class="text-xl font-bold text-blue-900">{{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}</h2>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($joueur->biographie, 60) }}</p>
                        </div>
                    </a>

                    <div class="bg-gray-50 p-4 border-t border-gray-100">
                        <div class="flex justify-center gap-4">
                            
                            <div class="vote-option">
                                <input type="radio" 
                                       id="rank1_p{{ $joueur->idpersonne }}" 
                                       name="rank_1" 
                                       value="{{ $joueur->idpersonne }}"
                                       class="hidden peer vote-radio"
                                       data-player="{{ $joueur->idpersonne }}"> <label for="rank1_p{{ $joueur->idpersonne }}" 
                                       class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-yellow-400 text-yellow-500 font-bold cursor-pointer hover:bg-yellow-50 peer-checked:bg-yellow-400 peer-checked:text-white transition-all shadow-sm ring-offset-2 peer-checked:ring-2 ring-yellow-200">
                                    1
                                </label>
                            </div>

                            <div class="vote-option">
                                <input type="radio" 
                                       id="rank2_p{{ $joueur->idpersonne }}" 
                                       name="rank_2" 
                                       value="{{ $joueur->idpersonne }}"
                                       class="hidden peer vote-radio"
                                       data-player="{{ $joueur->idpersonne }}"> <label for="rank2_p{{ $joueur->idpersonne }}" 
                                       class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-gray-400 text-gray-500 font-bold cursor-pointer hover:bg-gray-50 peer-checked:bg-gray-400 peer-checked:text-white transition-all shadow-sm ring-offset-2 peer-checked:ring-2 ring-gray-200">
                                    2
                                </label>
                            </div>

                            <div class="vote-option">
                                <input type="radio" 
                                       id="rank3_p{{ $joueur->idpersonne }}" 
                                       name="rank_3" 
                                       value="{{ $joueur->idpersonne }}"
                                       class="hidden peer vote-radio"
                                       data-player="{{ $joueur->idpersonne }}"> <label for="rank3_p{{ $joueur->idpersonne }}" 
                                       class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-orange-400 text-orange-500 font-bold cursor-pointer hover:bg-orange-50 peer-checked:bg-orange-400 peer-checked:text-white transition-all shadow-sm ring-offset-2 peer-checked:ring-2 ring-orange-200">
                                    3
                                </label>
                            </div>

                        </div>
                    </div>

                </div>

            @empty
                <p>Aucun joueur disponible pour le moment.</p>
            @endforelse
        </div>
        
        <div class="text-center mt-12 mb-8">
            <button type="submit" class="bg-blue-900 text-white px-8 py-3 rounded-full font-bold shadow-xl hover:bg-blue-800 transition transform hover:scale-105 inline-flex items-center gap-2">
                <span>Valider mon vote</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection