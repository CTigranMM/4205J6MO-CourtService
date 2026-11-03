@extends('layouts.app')

@section('title', __('Connexion'))

@section('content')
    <div class="content-box" style="max-width: 450px; margin: 0 auto;">
        <h1>{{ __('Connexion') }}</h1>

        <form method="post" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email">{{ __('Adresse courriel') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                @error('email') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('Mot de passe') }}</label>
                <input id="password" name="password" type="password" required>
                @error('password') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn">{{ __('Se connecter') }}</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
            </div>
        </form>
    </div>
@endsection

