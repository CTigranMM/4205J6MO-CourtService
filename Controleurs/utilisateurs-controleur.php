<?php
declare(strict_types=1);

require_once __DIR__ . '/../Modeles/utilisateurs-modele.php';

$nomProjet = 'ServiceCourt';
$titre = 'Liste des utilisateurs - ' . $nomProjet;

$utilisateurs = obtenirTousLesUtilisateurs($pdo);
require_once __DIR__ . '/../Vues/utilisateurs/index.php';