<?php
declare(strict_types=1);

require_once __DIR__ . '/config/securite.php';
require_once __DIR__ . '/Vues/Vue.php';
require_once __DIR__ . '/Modeles/Utilisateur.php';
require_once __DIR__ . '/Modeles/Reservation.php';
require_once __DIR__ . '/Modeles/Terrain.php';
require_once __DIR__ . '/Controleurs/ControleurAccueil.php';
require_once __DIR__ . '/Controleurs/ControleurRecits.php';
require_once __DIR__ . '/Controleurs/ControleurUtilisateur.php';
require_once __DIR__ . '/Controleurs/ControleurReservation.php';
require_once __DIR__ . '/Controleurs/ControleurErreur.php';

demarrerSession();

$action = $_GET['action'] ?? 'accueil';

try {
    require_once __DIR__ . '/config/database.php';

    $vue = new Vue();
    $controleurErreur = new ControleurErreur($vue);

    $utilisateursModele = new Utilisateur($pdo);
    $reservationsModele = new Reservation($pdo);
    $terrainsModele = new Terrain($pdo);

    $controleurAccueil = new ControleurAccueil($vue);
    $controleurRecits = new ControleurRecits($vue);
    $controleurUtilisateur = new ControleurUtilisateur($utilisateursModele, $reservationsModele, $vue, $controleurErreur);
    $controleurReservation = new ControleurReservation($reservationsModele, $terrainsModele, $vue, $controleurErreur);

    switch ($action) {
        case 'accueil':
            $controleurAccueil->index();
            break;
        case 'recits':
            $controleurRecits->index();
            break;
        case 'utilisateurs':
            $controleurUtilisateur->index();
            break;
        case 'utilisateur':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null) {
                $controleurErreur->afficher('Identifiant invalide.', 400);
                break;
            }
            $controleurUtilisateur->afficher($id);
            break;
        case 'reservation-formulaire':
            $utilisateurId = filter_input(INPUT_GET, 'utilisateur_id', FILTER_VALIDATE_INT);
            if ($utilisateurId === false || $utilisateurId === null) {
                $controleurErreur->afficher('Identifiant utilisateur invalide.', 400);
                break;
            }
            $controleurReservation->afficherFormulaireReservation($utilisateurId);
            break;
        case 'reservation-ajouter':
            $controleurReservation->traiterAjoutReservation();
            break;
        case 'confirmer-suppression-reservation':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $utilisateurId = filter_input(INPUT_GET, 'utilisateur_id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null || $utilisateurId === false || $utilisateurId === null) {
                $controleurErreur->afficher('Identifiants invalides.', 400);
                break;
            }
            $controleurReservation->afficherConfirmationSuppressionReservation($id, $utilisateurId);
            break;
        case 'supprimer-reservation':
            $controleurReservation->traiterSuppressionReservation();
            break;
        default:
            $controleurErreur->afficher('Page introuvable.', 404);
            break;
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    if (isset($controleurErreur)) {
        $controleurErreur->afficher('Une erreur inattendue est survenue. Détails : ' . $e->getMessage(), 500);
    } else {
        http_response_code(500);
        echo 'Erreur interne.';
    }
}
