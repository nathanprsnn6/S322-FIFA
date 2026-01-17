@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1000px; margin-top: 50px;">
    <h1 style="color: #b91c1c; margin-bottom: 30px;">Demandes des Professionnels</h1>

    @if(session('success'))
        <div style="background-color: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="padding: 15px; text-align: left;">Pro</th>
                    <th style="padding: 15px; text-align: left;">Sujet</th>
                    <th style="padding: 15px; text-align: left;">Message</th>
                    <th style="padding: 15px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($demandes as $demande)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; font-weight: bold;">{{ $demande->nom }} {{ $demande->prenom }}</td>
                        <td style="padding: 15px;">{{ $demande->sujet }}</td>
                        <td style="padding: 15px; color: #666;">{{ Str::limit($demande->message, 80) }}</td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('vente.demandes.create', $demande->iddemandeproduit) }}" 
                               style="background-color: #b91c1c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 0.9em;">
                                Traiter
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection