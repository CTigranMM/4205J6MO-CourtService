<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthentificationEtAccesTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_membre_peut_se_connecter_avec_de_bons_identifiants(): void
    {
        $user = User::factory()->create([
            'email' => 'membre@servicecourt.test',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'membre@servicecourt.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_connexion_echoue_avec_un_mauvais_mot_de_passe(): void
    {
        User::factory()->create([
            'email' => 'membre@servicecourt.test',
        ]);

        $this->post(route('login.store'), [
            'email' => 'membre@servicecourt.test',
            'password' => 'mauvais-mot-de-passe',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_un_utilisateur_connecte_peut_se_deconnecter(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_mon_compte_est_refuse_aux_invites_et_affiche_uniquement_ses_reservations(): void
    {
        $user = User::factory()->create();
        $autreUser = User::factory()->create();

        $terrainPropre = Terrain::factory()->create(['nom_terrain' => 'Court Alpha Compte']);
        $terrainAutre = Terrain::factory()->create(['nom_terrain' => 'Court Omega Prive']);

        Reservation::factory()->for($terrainPropre)->for($user)->create();
        Reservation::factory()->for($terrainAutre)->for($autreUser)->create();

        $this->get(route('account.show'))
            ->assertRedirect(route('login'));

        $this->actingAs($user)
            ->get(route('account.show'))
            ->assertOk()
            ->assertSee('Court Alpha Compte')
            ->assertDontSee('Court Omega Prive');
    }

    public function test_administration_autorise_un_gestionnaire_et_refuse_un_membre(): void
    {
        $membre = User::factory()->create();
        $manager = User::factory()->manager()->create();

        $this->get(route('admin.index'))
            ->assertRedirect(route('login'));

        $this->actingAs($membre)
            ->get(route('admin.index'))
            ->assertForbidden();

        $this->actingAs($manager)
            ->get(route('admin.index'))
            ->assertOk();
    }

    public function test_recherche_asynchrone_retourne_les_reservations_et_valide_la_requete(): void
    {
        $terrain = Terrain::factory()->create(['nom_terrain' => 'Court Philippe-Chatrier']);
        $autreTerrain = Terrain::factory()->create(['nom_terrain' => 'Court Arthur Ashe']);

        $reservation = Reservation::factory()->for($terrain)->create();
        Reservation::factory()->for($autreTerrain)->create();

        $this->getJson(route('recherche.reservations', ['q' => 'Chatrier']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $reservation->id,
                'label' => 'Court Philippe-Chatrier',
            ]);

        $this->getJson(route('recherche.reservations', ['q' => 'a']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('q');
    }
}
