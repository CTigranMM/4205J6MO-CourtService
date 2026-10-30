<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceCourt - @yield('title', 'Réservations')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('scripts')
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('reservations.index') }}" class="brand">ServiceCourt</a>
        </div>
    </header>

    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
