@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start mb-12">
            
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-blue-900 mb-8 uppercase font-bebas tracking-wide">
                    {{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}
                </h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm">
                    <div class="space-y-3">
                        <p><span class="font-bold text-blue-900">Poste :</span> Attaquant </p>
                        <p><span class="font-bold text-blue-900">Date de naissance :</span> {{ $joueur->personne->datenaissance }}</p>
                        <p><span class="font-bold text-blue-900">Lieu de naissance :</span> {{ $joueur->personne->lieunaissance }}</p>
                        <p><span class="font-bold text-blue-900">Pied préféré :</span> {{ $joueur->piedprefere }}</p>
                        <p><span class="font-bold text-blue-900">Club (ID) :</span> {{ $joueur->idequipe }}</p>
                    </div>

                    <div class="space-y-3">
                        <p><span class="font-bold text-blue-900">1ère sélection :</span> BD </p>
                        <p><span class="font-bold text-blue-900">Nombre de sélections :</span> {{ $joueur->nbselection }}</p>
                        <p><span class="font-bold text-blue-900">Poids :</span> {{ $joueur->poids }} kg</p>
                        <p><span class="font-bold text-blue-900">Taille :</span> {{ $joueur->taille }} cm</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 md:mt-0 md:ml-8">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b3/Kylian_Mbapp%C3%A9_2018.jpg" 
                     alt="Photo joueur" 
                     class="w-48 h-48 object-cover rounded-full md:rounded-none md:w-64 md:h-auto object-top mask-image-gradient">
            </div>
        </div>

        <hr class="border-gray-200 my-8">

        <div class="mb-12">
            <h2 class="text-2xl font-bold text-blue-900 mb-4 uppercase font-bebas">BIOGRAPHIE</h2>
            <div class="text-sm space-y-1 text-slate-700">
                @if(isset($player['bio']))
                    @foreach($player['bio'] as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                @else
                    <p>{{ $joueur->biographie }}</p>
                @endif
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-blue-900 mb-6 uppercase font-bebas">STATISTIQUES</h2>
            
            <div class="flex flex-wrap justify-center md:justify-between gap-4">
                @if(isset($player['stats']))
                    @foreach($player['stats'] as $stat)
                    <div class="hexagon-wrapper">
                        <div class="hexagon">
                            <div class="hexagon-inner text-center">
                                <span class="text-[10px] font-bold text-blue-900 uppercase tracking-tight mb-1">{{ $stat['label'] }}</span>
                                <span class="text-4xl font-bebas text-blue-900">{{ $stat['value'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
@endsection