@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Suppression des utilisateurs inactifs (+2 ans)</h1>

    <section class="card" aria-label="Liste des utilisateurs à supprimer">
        <div class="card-head">
            <p class="subtitle">
            Liste des personnes dont la dernière connexion date de plus de 2 ans.
            Cliquez sur “Supprimer” pour retirer l’utilisateur de la base.
            </p>
            <span class="badge"><span class="dot" aria-hidden="true"></span> Action irréversible</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nom / Prénom</th>
                    <th>Dernière connexion</th>
                    <th class="actions">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="name">{{ $u->nom }} {{ $u->prenom }}</div>
                            <div class="muted">ID: {{ $u->idpersonne }}</div>
                        </td>
                        <td class="muted">{{ \Carbon\Carbon::parse($u->last_login_date)->format('d/m/Y') }}</td>
                        <td class="actions">
                            <form method="POST" action="{{ route('dpd.users.destroy', $u->idpersonne) }}"
                                onsubmit="return confirm('Confirmer la suppression de {{ $u->nom }} {{ $u->prenom }} ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">Aucun utilisateur inactif depuis plus de 2 ans.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection