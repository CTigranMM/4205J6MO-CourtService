<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreationReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_personne_connectee_peut_creer_une_reservation(): void
    {
        // Préparer
        $user = User::factory()->create(['email_verified_at' => now()]);
        $terrain = Terrain::factory()->create();
        $debut = now()->addDays(2)->format('Y-m-d\TH:i');
        $fin = now()->addDays(2)->addHour()->format('Y-m-d\TH:i');

        // Agir
        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => $debut,
            'date_heure_fin' => $fin,
            'statut' => 'confirmee',
        ]);

        // Vérifier
        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'terrain_id' => $terrain->id,
            'user_id' => $user->id,
            'statut' => 'confirmee',
        ]);
    }

    public function test_une_date_de_fin_anterieure_au_debut_est_refusee(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $terrain = Terrain::factory()->create();

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'terrain_id' => $terrain->id,
                'date_heure_debut' => now()->addDays(2)->addHours(2)->format('Y-m-d\TH:i'),
                'date_heure_fin' => now()->addDays(2)->addHour()->format('Y-m-d\TH:i'),
            ])
            ->assertSessionHasErrors('date_heure_fin');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_un_terrain_inexistant_est_refuse(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'terrain_id' => 999999,
                'date_heure_debut' => now()->addDays(2)->format('Y-m-d\TH:i'),
                'date_heure_fin' => now()->addDays(2)->addHour()->format('Y-m-d\TH:i'),
            ])
            ->assertSessionHasErrors('terrain_id');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_un_autre_compte_ne_peut_pas_modifier_la_reservation(): void
    {
        $reservation = Reservation::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->put(route('reservations.update', $reservation), [
                'terrain_id' => $reservation->terrain_id,
                'date_heure_debut' => now()->addDays(6)->format('Y-m-d\TH:i'),
                'date_heure_fin' => now()->addDays(6)->addHour()->format('Y-m-d\TH:i'),
                'statut' => 'annulee',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'terrain_id' => $reservation->terrain_id,
            'statut' => $reservation->statut,
            'user_id' => $reservation->user_id,
        ]);
    }

    public function test_un_autre_compte_ne_peut_pas_supprimer_la_reservation(): void
    {
        $reservation = Reservation::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->delete(route('reservations.destroy', $reservation))
            ->assertForbidden();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'user_id' => $reservation->user_id,
        ]);
    }

    public function test_un_gestionnaire_peut_modifier_la_reservation_d_un_membre(): void
    {
        $reservation = Reservation::factory()->moinsDe24Heures()->create();
        $manager = User::factory()->manager()->create();

        $this->actingAs($manager)
            ->put(route('reservations.update', $reservation), [
                'terrain_id' => $reservation->terrain_id,
                'date_heure_debut' => now()->addDays(4)->format('Y-m-d\TH:i'),
                'date_heure_fin' => now()->addDays(4)->addHour()->format('Y-m-d\TH:i'),
                'statut' => 'annulee',
            ])
            ->assertRedirect(route('reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'statut' => 'annulee',
        ]);
    }

    public function test_consultation_publique_liste_et_detail_retournent_200_ou_404(): void
    {
        $terrain = Terrain::factory()->create(['nom_terrain' => 'Court Central Test']);
        $reservation = Reservation::factory()->for($terrain)->create();

        $this->get(route('reservations.index'))
            ->assertOk()
            ->assertSee('Court Central Test');

        $this->get(route('reservations.show', $reservation))
            ->assertOk()
            ->assertSee('Court Central Test');

        $this->get('/reservations/999999')
            ->assertNotFound();
    }

    public function test_ecriture_sans_connexion_redirige_vers_connexion_sans_creer_de_reservation(): void
    {
        $terrain = Terrain::factory()->create();

        $this->post(route('reservations.store'), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'date_heure_fin' => now()->addDays(2)->addHour()->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('reservations', 0);
    }
}
