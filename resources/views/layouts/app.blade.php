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
            <a href="{{ route('home') }}" class="brand">ServiceCourt</a>

            <div class="nav-links">
                <a href="{{ route('reservations.index') }}">{{ __('Réservations') }}</a>
                @auth
                    <span>{{ auth()->user()->name }}</span>
                    <a href="{{ route('account.show') }}">{{ __('Mon compte') }}</a>
                    @if (auth()->user()?->role === \App\Models\User::ROLE_MANAGER)
                        <a href="{{ route('admin.index') }}">{{ __('Administration') }}</a>
                    @endif
                    <form method="post" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit">{{ __('Se déconnecter') }}</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}">{{ __('Connexion') }}</a>
                    <a href="{{ route('register') }}">{{ __('Inscription') }}</a>
                @endguest
            </div>

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
