<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReservationDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_reservation_peut_recevoir_un_document_image(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $terrain = Terrain::factory()->create();

        $this->actingAs($user)->post(route('reservations.store'), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'date_heure_fin' => now()->addDays(2)->addHour()->format('Y-m-d\TH:i'),
            'statut' => 'confirmee',
            'document' => UploadedFile::fake()->image('photo.png'),
        ])->assertRedirect();

        $reservation = Reservation::firstOrFail();
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
        Storage::disk('public')->assertExists($reservation->document_path);
    }

    public function test_un_fichier_texte_est_refuse_comme_document(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $terrain = Terrain::factory()->create();

        $this->actingAs($user)->post(route('reservations.store'), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'date_heure_fin' => now()->addDays(2)->addHour()->format('Y-m-d\TH:i'),
            'document' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors('document');

        $this->assertDatabaseCount('reservations', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_le_remplacement_du_document_supprime_l_ancien_fichier(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $terrain = Terrain::factory()->create();

        $this->actingAs($user)->post(route('reservations.store'), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'date_heure_fin' => now()->addDays(3)->addHour()->format('Y-m-d\TH:i'),
            'document' => UploadedFile::fake()->image('ancien.png'),
        ])->assertRedirect();

        $reservation = Reservation::firstOrFail();
        $ancienChemin = $reservation->document_path;

        $this->actingAs($user)->put(route('reservations.update', $reservation), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => now()->addDays(4)->format('Y-m-d\TH:i'),
            'date_heure_fin' => now()->addDays(4)->addHour()->format('Y-m-d\TH:i'),
            'statut' => 'confirmee',
            'document' => UploadedFile::fake()->image('nouveau.png'),
        ])->assertRedirect();

        $reservation->refresh();
        Storage::disk('public')->assertMissing($ancienChemin);
        Storage::disk('public')->assertExists($reservation->document_path);
    }

    public function test_la_suppression_du_document_est_protegee_et_supprime_le_fichier(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $autreUser = User::factory()->create();
        $terrain = Terrain::factory()->create();

        $this->actingAs($user)->post(route('reservations.store'), [
            'terrain_id' => $terrain->id,
            'date_heure_debut' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'date_heure_fin' => now()->addDays(3)->addHour()->format('Y-m-d\TH:i'),
            'document' => UploadedFile::fake()->image('preuve.jpg'),
        ])->assertRedirect();

        $reservation = Reservation::firstOrFail();
        $chemin = $reservation->document_path;

        $this->actingAs($autreUser)
            ->delete(route('reservations.document.destroy', $reservation))
            ->assertForbidden();

        Storage::disk('public')->assertExists($chemin);
        $this->assertNotNull($reservation->fresh()->document_path);

        $this->actingAs($user)
            ->delete(route('reservations.document.destroy', $reservation))
            ->assertRedirect();

        Storage::disk('public')->assertMissing($chemin);
        $this->assertNull($reservation->fresh()->document_path);
    }
}
