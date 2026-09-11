<?php
declare(strict_types=1);

require_once __DIR__ . '/../Modeles/utilisateurs-modele.php';

$nomProjet = 'ServiceCourt';
$titre = 'Liste des utilisateurs - ' . $nomProjet;

try {
    require_once __DIR__ . '/../config/bd.php';

    $utilisateurs = obtenirTousLesUtilisateurs($pdo);
    require_once __DIR__ . '/../Vues/utilisateurs/index.php';
} catch (Throwable $e) {
    error_log($e->getMessage());

    $message = "Une erreur est survenue lors de la communication avec la base de données. Veuillez réessayer plus tard.";
    require_once __DIR__ . '/../Vues/erreur.php';
}