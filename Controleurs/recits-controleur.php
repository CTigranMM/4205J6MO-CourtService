<?php
declare(strict_types=1);

function afficherRecits(): void
{
    $nomProjet = 'ServiceCourt';
    $titrePage = 'Récits - ' . $nomProjet;

    require __DIR__ . '/../Vues/recits.php';
}
