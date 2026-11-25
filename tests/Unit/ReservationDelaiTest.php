<?php

namespace Tests\Unit;

use App\Models\Reservation;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class ReservationDelaiTest extends TestCase
{
    public function test_une_reservation_a_plus_de_24h_est_modifiable(): void
    {
        $maintenant = CarbonImmutable::parse('2026-11-25 10:00:00');
        $debut = $maintenant->addHours(24);

        $this->assertTrue(Reservation::estDansDelaiModification($debut, $maintenant));
    }

    public function test_une_reservation_a_moins_de_24h_n_est_pas_modifiable(): void
    {
        $maintenant = CarbonImmutable::parse('2026-11-25 10:00:00');
        $debut = $maintenant->addHours(2);

        $this->assertFalse(Reservation::estDansDelaiModification($debut, $maintenant));
    }

    public function test_une_reservation_passee_n_est_pas_modifiable(): void
    {
        $maintenant = CarbonImmutable::parse('2026-11-25 10:00:00');
        $debut = $maintenant->subDay();

        $this->assertFalse(Reservation::estDansDelaiModification($debut, $maintenant));
    }

    public function test_une_reservation_sans_date_de_debut_n_est_pas_modifiable(): void
    {
        $this->assertFalse(Reservation::estDansDelaiModification(null));
    }
}
