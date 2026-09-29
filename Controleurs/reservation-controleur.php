<?php
declare(strict_types=1);

require_once __DIR__ . '/../Modeles/reservations-modele.php';
require_once __DIR__ . '/../Modeles/terrain-modele.php';

function afficherFormulaireReservation(PDO $pdo, int $utilisateurId, array $donnees = [], array $erreurs = []): void
{
    $terrains = obtenirTousLesTerrains($pdo);
    require __DIR__ . '/../Vues/reservations/ajouter.php';
    //
}

function traiterAjoutReservation(PDO $pdo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        afficherErreur('Méthode non permise.', 405);
        return;
    }

    if (!verifierJetonCsrf($_POST['jeton_csrf'] ?? null)) {
        afficherErreur('Jeton CSRF invalide.', 403);
        return;
    }

    $utilisateurId = filter_input(INPUT_POST, 'utilisateur_id', FILTER_VALIDATE_INT);
    if ($utilisateurId === false || $utilisateurId === null) {
        afficherErreur('Identifiant utilisateur invalide.', 400);
        return;
    }

    $donnees = [
        'terrains_id' => trim($_POST['terrains_id'] ?? ''),
        'date_heure_debut' => trim($_POST['date_heure_debut'] ?? ''),
        'date_heure_fin' => trim($_POST['date_heure_fin'] ?? '')
    ];

    $erreurs = [];

    if (empty($donnees['terrains_id']) || !is_numeric($donnees['terrains_id'])) {
        $erreurs['terrains_id'] = 'Le terrain est requis et doit être valide.';
    }

    if (empty($donnees['date_heure_debut'])) {
        $erreurs['date_heure_debut'] = 'La date de début est requise.';
    }

    if (empty($donnees['date_heure_fin'])) {
        $erreurs['date_heure_fin'] = 'La date de fin est requise.';
    }

    if (!empty($erreurs)) {
        afficherFormulaireReservation($pdo, $utilisateurId, $donnees, $erreurs);
        return;
    }

    ajouterReservation($pdo, [
        'utilisateur_id' => $utilisateurId,
        'terrains_id' => (int) $donnees['terrains_id'],
        'date_heure_debut' => $donnees['date_heure_debut'],
        'date_heure_fin' => $donnees['date_heure_fin'],
        'statut' => 'confirmee'
    ]);

    header('Location: index.php?action=utilisateur&id=' . $utilisateurId);
    exit;
}

function afficherConfirmationSuppressionReservation(PDO $pdo, int $id, int $utilisateurId): void
{
    $reservation = obtenirReservation($pdo, $id);

    if ($reservation === null) {
        afficherErreur('Réservation introuvable.', 404);
        return;
    }

    require __DIR__ . '/../Vues/reservations/confirmer-suppression.php';
}

function traiterSuppressionReservation(PDO $pdo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        afficherErreur('Méthode non permise.', 405);
        return;
    }

    if (!verifierJetonCsrf($_POST['jeton_csrf'] ?? null)) {
        afficherErreur('Jeton CSRF invalide.', 403);
        return;
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $utilisateurId = filter_input(INPUT_POST, 'utilisateur_id', FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $utilisateurId === false || $utilisateurId === null) {
        afficherErreur('Identifiant invalide.', 400);
        return;
    }

    $reservation = obtenirReservation($pdo, $id);
    if ($reservation === null) {
        afficherErreur('Réservation introuvable.', 404);
        return;
    }

    supprimerReservation($pdo, $id);

    header('Location: index.php?action=utilisateur&id=' . $utilisateurId);
    exit;
}
