<?php
declare(strict_types=1);

require_once __DIR__ . '/Controleurs/erreur-controleur.php';

$action = $_GET['action'] ?? 'accueil';

try {
    require_once __DIR__ . '/config/bd.php';

    switch ($action) {
        case 'accueil':
            require_once __DIR__ . '/Controleurs/accueil-controleur.php';
            break;
        case 'recits':
            require_once __DIR__ . '/Controleurs/recits-controleur.php';
            break;
        case 'utilisateurs':
            require_once __DIR__ . '/Controleurs/utilisateurs-controleur.php';
            break;
        default:
            afficherErreur('Page introuvable.', 404);
            break;
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    afficherErreur('Une erreur inattendue est survenue.', 500);
}