<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $debut = fake()->dateTimeBetween('+1 days', '+10 days');
        $fin = (clone $debut)->modify('+1 hour');

        return [
            'terrain_id' => Terrain::factory(),
            'user_id' => User::factory(),
            'date_heure_debut' => $debut,
            'date_heure_fin' => $fin,
            'statut' => 'confirmee',
        ];
    }

    /**
     * Cas limite : réservation dans moins de 24h (pour tester l'interdiction ou autorisation d'annulation)
     */
    public function moinsDe24Heures(): static
    {
        return $this->state(function (array $attributes) {
            $debut = now()->addHours(2);
            return [
                'date_heure_debut' => $debut,
                'date_heure_fin' => (clone $debut)->addHour(),
            ];
        });
    }

    /**
     * Cas limite : réservation déjà passée
     */
    public function passee(): static
    {
        return $this->state(function (array $attributes) {
            $debut = now()->subDays(2);
            return [
                'date_heure_debut' => $debut,
                'date_heure_fin' => (clone $debut)->addHour(),
            ];
        });
    }

    /**
     * Cas limite : réservation annulée
     */
    public function annulee(): static
    {
        return $this->state(fn () => [
            'statut' => 'annulee',
        ]);
    }
}
