@extends('layouts.app')

@section('title', __('Inscription'))

@section('content')
    <div class="content-box" style="max-width: 500px; margin: 0 auto;">
        <h1>{{ __('Créer un compte') }}</h1>

        <form method="post" action="{{ route('register.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('Nom') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
                @error('name') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('Adresse courriel') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="email_confirmation">{{ __('Confirmer l\'adresse courriel') }}</label>
                <input id="email_confirmation" name="email_confirmation" type="email" value="{{ old('email_confirmation') }}" required>
            </div>

            <div class="form-group">
                <label for="password">{{ __('Mot de passe') }}</label>
                <input id="password" name="password" type="password" required>
                @error('password') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirmation') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                @error('g-recaptcha-response') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn">{{ __('Créer le compte') }}</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

