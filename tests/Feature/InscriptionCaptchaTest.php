<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InscriptionCaptchaTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_captcha_refuse_empeche_l_inscription(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' =>
                Http::response(['success' => false], 200),
        ]);

        $this->post(route('register.store'), [
            'name' => 'Compte test',
            'email' => 'refus@example.test',
            'email_confirmation' => 'refus@example.test',
            'password' => 'mot-de-passe-test',
            'password_confirmation' => 'mot-de-passe-test',
            'g-recaptcha-response' => 'jeton-test',
        ])->assertSessionHasErrors('g-recaptcha-response');

        $this->assertDatabaseMissing('users', ['email' => 'refus@example.test']);
    }

    public function test_un_captcha_valide_permet_l_inscription(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' =>
                Http::response(['success' => true], 200),
        ]);

        $this->post(route('register.store'), [
            'name' => 'Joueur Valide',
            'email' => 'valide@example.test',
            'email_confirmation' => 'valide@example.test',
            'password' => 'mot-de-passe-test',
            'password_confirmation' => 'mot-de-passe-test',
            'g-recaptcha-response' => 'jeton-valide',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'valide@example.test',
            'role' => User::ROLE_MEMBER,
        ]);
    }

    public function test_une_indisponibilite_recaptcha_empeche_l_inscription(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => function () {
                throw new ConnectionException('Service inaccessible');
            },
        ]);

        $this->post(route('register.store'), [
            'name' => 'Compte panne',
            'email' => 'panne@example.test',
            'email_confirmation' => 'panne@example.test',
            'password' => 'mot-de-passe-test',
            'password_confirmation' => 'mot-de-passe-test',
            'g-recaptcha-response' => 'jeton-panne',
        ])->assertSessionHasErrors('g-recaptcha-response');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'panne@example.test']);
    }
}
