<?php
declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class Terrain extends Modele
{


    public function obtenirTousLesTerrains(): array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom_terrain, surface, emplacement
             FROM terrains
             ORDER BY nom_terrain'
        );
        $requete->execute();
        return $requete->fetchAll();
    }

}
