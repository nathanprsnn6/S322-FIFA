@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Utilisateurs</h1>

        <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th>Login (Table Utilisateur)</th>
                    <th>Nom (Table Personne)</th>
                    <th>Prénom (Table Personne)</th>
                    <th>Date de naissance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($utilisateurs as $user)
                    <tr>

                        <td>{{ $user->mdp }}</td>

                        <td>
                            {{ $user->personne ? $user->personne->nom : 'Inconnu' }}
                        </td>
                        <td>
                            {{ $user->personne ? $user->personne->prenom : 'Inconnu' }}
                        </td>
                        <td>
                             {{ $user->personne ? $user->personne->datenaissance : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection