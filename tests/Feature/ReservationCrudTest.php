<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Terrain $terrain;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->terrain = Terrain::factory()->create();
    }

    public function test_peut_afficher_la_liste_des_reservations(): void
    {
        $reservation = Reservation::factory()->for($this->terrain)->for($this->user)->create();

        $response = $this->get(route('reservations.index'));

        $response->assertStatus(200);
        $response->assertSee($this->terrain->nom_terrain);
    }

    public function test_peut_afficher_le_formulaire_de_creation(): void
    {
        $response = $this->get(route('reservations.create'));

        $response->assertStatus(200);
        $response->assertSee($this->terrain->nom_terrain);
    }

    public function test_peut_enregistrer_une_nouvelle_reservation(): void
    {
        $debut = now()->addDays(2)->format('Y-m-d\TH:i');
        $fin = now()->addDays(2)->addHour()->format('Y-m-d\TH:i');

        $response = $this->post(route('reservations.store'), [
            'terrain_id' => $this->terrain->id,
            'date_heure_debut' => $debut,
            'date_heure_fin' => $fin,
            'statut' => 'confirmee',
        ]);

        $this->assertDatabaseHas('reservations', [
            'terrain_id' => $this->terrain->id,
            'statut' => 'confirmee',
        ]);

        $reservation = Reservation::latest('id')->first();
        $response->assertRedirect(route('reservations.show', $reservation));
        $response->assertSessionHas('status', 'Réservation créée avec succès.');
    }

    public function test_la_validation_echoue_si_champs_manquants(): void
    {
        $response = $this->post(route('reservations.store'), []);

        $response->assertSessionHasErrors(['terrain_id', 'date_heure_debut', 'date_heure_fin']);
    }

    public function test_peut_afficher_le_detail_d_une_reservation(): void
    {
        $reservation = Reservation::factory()->for($this->terrain)->for($this->user)->create();

        $response = $this->get(route('reservations.show', $reservation));

        $response->assertStatus(200);
        $response->assertSee($this->terrain->nom_terrain);
        $response->assertSee($this->user->name);
    }

    public function test_peut_afficher_le_formulaire_de_modification_pre_rempli(): void
    {
        $reservation = Reservation::factory()->for($this->terrain)->for($this->user)->create();

        $response = $this->get(route('reservations.edit', $reservation));

        $response->assertStatus(200);
        $response->assertSee($reservation->date_heure_debut->format('Y-m-d\TH:i'));
    }

    public function test_peut_modifier_une_reservation(): void
    {
        $reservation = Reservation::factory()->for($this->terrain)->for($this->user)->create();

        $nouveauDebut = now()->addDays(5)->format('Y-m-d\TH:i');
        $nouveauFin = now()->addDays(5)->addHour()->format('Y-m-d\TH:i');

        $response = $this->patch(route('reservations.update', $reservation), [
            'terrain_id' => $this->terrain->id,
            'date_heure_debut' => $nouveauDebut,
            'date_heure_fin' => $nouveauFin,
            'statut' => 'annulee',
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'statut' => 'annulee',
        ]);

        $response->assertRedirect(route('reservations.show', $reservation));
        $response->assertSessionHas('status', 'Réservation modifiée avec succès.');
    }

    public function test_peut_supprimer_une_reservation(): void
    {
        $reservation = Reservation::factory()->for($this->terrain)->for($this->user)->create();

        $response = $this->delete(route('reservations.destroy', $reservation));

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);

        $response->assertRedirect(route('reservations.index'));
        $response->assertSessionHas('status', 'Réservation supprimée avec succès.');
    }
}
