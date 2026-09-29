<?php
declare(strict_types=1);

function obtenirTousLesTerrains(PDO $pdo): array
{
    $requete = $pdo->prepare(
        'SELECT id, nom_terrain, surface, emplacement
         FROM terrains
         ORDER BY nom_terrain'
    );
    $requete->execute();
    return $requete->fetchAll();
}
