<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <header class="fifa-header">
        
        <div class="header-left">
            <a href="#" class="logo-fifa">FIFA</a>
            
            <a href="#" class="nav-link">Boutique</a>
        </div>

        <a href="inscriptionConnexion" class="btn-auth">
            <span class="user-icon"></span> Inscription / Connexion
        </a>

    </header>

    <main>
        @yield('content')
    </main>

    @yield('scripts')

</body>
</html>