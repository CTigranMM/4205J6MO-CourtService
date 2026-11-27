<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

interface EnvoiAlerteReservation
{
    public function envoyer(string $message): bool;
}

class AlerteReservation
{
    public function __construct(private EnvoiAlerteReservation $envoi) {}

    public function avertir(string $nomTerrain): bool
    {
        return $this->envoi->envoyer("Réservation : $nomTerrain");
    }
}

class DoubluresTest extends TestCase
{
    public function test_stub_fournit_une_reponse_prevue(): void
    {
        $envoi = $this->createStub(EnvoiAlerteReservation::class);
        $envoi->method('envoyer')->willReturn(true);

        $this->assertTrue((new AlerteReservation($envoi))->avertir('Court Central'));
    }

    public function test_mock_verifie_l_appel_attendu(): void
    {
        $envoi = $this->createMock(EnvoiAlerteReservation::class);
        $envoi->expects($this->once())
            ->method('envoyer')
            ->with('Réservation : Court Central')
            ->willReturn(true);

        $this->assertTrue((new AlerteReservation($envoi))->avertir('Court Central'));
    }
}
