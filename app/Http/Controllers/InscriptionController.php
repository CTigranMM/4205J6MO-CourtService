<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'confirmed', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $token = $request->validate([
            'g-recaptcha-response' => ['required', 'string'],
        ])['g-recaptcha-response'];

        try {
            $verification = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
            ]);
        } catch (ConnectionException $e) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Vérification indisponible. Réessayez.',
            ]);
        }

        if (! $verification->successful() || $verification->json('success') !== true) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Veuillez refaire la vérification.',
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => User::ROLE_MEMBER,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('status', __('Votre compte a été créé.'));
    }
}

