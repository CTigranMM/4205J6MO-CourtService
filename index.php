<?php
declare(strict_types=1);

require_once __DIR__ . '/config/securite.php';
require_once __DIR__ . '/Controleurs/accueil-controleur.php';
require_once __DIR__ . '/Controleurs/recits-controleur.php';
require_once __DIR__ . '/Controleurs/utilisateurs-controleur.php';
require_once __DIR__ . '/Controleurs/reservation-controleur.php';
require_once __DIR__ . '/Controleurs/erreur-controleur.php';

demarrerSession();

$action = $_GET['action'] ?? 'accueil';

try {
    require_once __DIR__ . '/config/database.php';

    switch ($action) {
        case 'accueil':
            afficherAccueil();
            break;
        case 'recits':
            afficherRecits();
            break;
        case 'utilisateurs':
            afficherListeUtilisateurs($pdo);
            break;
        case 'utilisateur':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null) {
                afficherErreur('Identifiant invalide.', 400);
                break;
            }
            afficherUtilisateur($pdo, $id);
            break;
        case 'reservation-formulaire':
            $utilisateurId = filter_input(INPUT_GET, 'utilisateur_id', FILTER_VALIDATE_INT);
            if ($utilisateurId === false || $utilisateurId === null) {
                afficherErreur('Identifiant utilisateur invalide.', 400);
                break;
            }
            afficherFormulaireReservation($pdo, $utilisateurId);
            break;
        case 'reservation-ajouter':
            traiterAjoutReservation($pdo);
            break;
        case 'confirmer-suppression-reservation':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $utilisateurId = filter_input(INPUT_GET, 'utilisateur_id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null || $utilisateurId === false || $utilisateurId === null) {
                afficherErreur('Identifiants invalides.', 400);
                break;
            }
            afficherConfirmationSuppressionReservation($pdo, $id, $utilisateurId);
            break;
        case 'supprimer-reservation':
            traiterSuppressionReservation($pdo);
            break;
        default:
            afficherErreur('Page introuvable.', 404);
            break;
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    afficherErreur('Une erreur inattendue est survenue. Détails : ' . $e->getMessage(), 500);
}