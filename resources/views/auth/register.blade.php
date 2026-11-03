@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <h1>Créer un compte</h1>

    <form method="post" action="{{ route('register.store') }}">
        @csrf

        <label for="name">Nom</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required>
        @error('name') <p>{{ $message }}</p> @enderror

        <label for="email">Adresse courriel</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
        @error('email') <p>{{ $message }}</p> @enderror

        <label for="email_confirmation">Confirmer l'adresse courriel</label>
        <input id="email_confirmation" name="email_confirmation" type="email" value="{{ old('email_confirmation') }}" required>

        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" required>
        @error('password') <p>{{ $message }}</p> @enderror

        <label for="password_confirmation">Confirmation</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>

        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        @error('g-recaptcha-response') <p>{{ $message }}</p> @enderror

        <button type="submit">Créer le compte</button>
    </form>
@endsection

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
