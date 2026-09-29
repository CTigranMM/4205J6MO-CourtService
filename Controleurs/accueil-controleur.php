<?php
declare(strict_types=1);

function afficherAccueil(): void
{
    $nomProjet = 'ServiceCourt';
    $titrePage = 'Accueil - ' . $nomProjet;
    $auteur = 'Michael Matinyan';
    $versionPhp = PHP_VERSION;

    require __DIR__ . '/../Vues/accueil.php';
}
