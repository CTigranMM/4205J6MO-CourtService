<?php
declare(strict_types=1);

// Configuration de la sécurité (CSRF, session)
require_once __DIR__ . '/config/securite.php';
demarrerSession();

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
            afficherListeUtilisateurs($pdo);
            break;
        case 'utilisateur':
            require_once __DIR__ . '/Controleurs/utilisateurs-controleur.php';
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null) {
                afficherErreur('Identifiant invalide.', 400);
            } else {
                afficherUtilisateur($pdo, $id);
            }
            break;
        case 'reservation-formulaire':
            require_once __DIR__ . '/Controleurs/reservation-controleur.php';
            $utilisateurId = filter_input(INPUT_GET, 'utilisateur_id', FILTER_VALIDATE_INT);
            if ($utilisateurId === false || $utilisateurId === null) {
                afficherErreur('Identifiant utilisateur invalide.', 400);
            } else {
                afficherFormulaireReservation($pdo, $utilisateurId);
            }
            break;
        case 'reservation-ajouter':
            require_once __DIR__ . '/Controleurs/reservation-controleur.php';
            traiterAjoutReservation($pdo);
            break;
        case 'confirmer-suppression-reservation':
            require_once __DIR__ . '/Controleurs/reservation-controleur.php';
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $utilisateurId = filter_input(INPUT_GET, 'utilisateur_id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null || $utilisateurId === false || $utilisateurId === null) {
                afficherErreur('Identifiants invalides.', 400);
            } else {
                afficherConfirmationSuppressionReservation($pdo, $id, $utilisateurId);
            }
            break;
        case 'supprimer-reservation':
            require_once __DIR__ . '/Controleurs/reservation-controleur.php';
            traiterSuppressionReservation($pdo);
            break;
        default:
            afficherErreur('Page introuvable.', 404);
            break;
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    afficherErreur('Une erreur inattendue est survenue.', 500);
}