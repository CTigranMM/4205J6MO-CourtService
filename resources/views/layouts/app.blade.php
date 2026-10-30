<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceCourt - @yield('title', 'Réservations')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('scripts')
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('reservations.index') }}" class="brand">ServiceCourt</a>

            <form method="post" action="{{ route('locale.update') }}">
                @csrf
                <label for="locale">{{ __('Langue') }}</label>
                <select id="locale" name="locale">
                    <option value="fr" @selected(app()->getLocale() === 'fr')>Français</option>
                    <option value="en" @selected(app()->getLocale() === 'en')>English</option>
                </select>
                <button type="submit">{{ __('Appliquer') }}</button>
            </form>
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
