<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/securite.php';
require_once __DIR__ . '/Services/Authentification.php';
require_once __DIR__ . '/Vues/Vue.php';
require_once __DIR__ . '/Modeles/Utilisateur.php';
require_once __DIR__ . '/Modeles/Reservation.php';
require_once __DIR__ . '/Modeles/Terrain.php';
require_once __DIR__ . '/Controleurs/ControleurAccueil.php';
require_once __DIR__ . '/Controleurs/ControleurRecits.php';
require_once __DIR__ . '/Controleurs/ControleurUtilisateur.php';
require_once __DIR__ . '/Controleurs/ControleurReservation.php';
require_once __DIR__ . '/Controleurs/ControleurErreur.php';
require_once __DIR__ . '/Routage/Routeur.php';

demarrerSession();

$auth = new Authentification();
$vue = new Vue();
$controleurErreur = new ControleurErreur($vue);

$utilisateursModele = new Utilisateur($pdo);
$reservationsModele = new Reservation($pdo);
$terrainsModele = new Terrain($pdo);

$controleurAccueil = new ControleurAccueil($vue);
$controleurRecits = new ControleurRecits($vue);
$controleurUtilisateur = new ControleurUtilisateur($utilisateursModele, $reservationsModele, $vue, $controleurErreur, $auth);
$controleurReservation = new ControleurReservation($reservationsModele, $terrainsModele, $vue, $controleurErreur);

$routeur = new Routeur($controleurAccueil, $controleurRecits, $controleurUtilisateur, $controleurReservation, $controleurErreur);

try {
    $routeur->router();
} catch (Throwable $exception) {
    $statut = in_array($exception->getCode(), [400, 403, 404, 405], true)
        ? $exception->getCode()
        : 500;
    $message = $statut === 500
        ? 'Une erreur empêche le traitement de la demande.'
        : $exception->getMessage();

    if ($statut === 500) {
        error_log($exception->getMessage());
    }

    $controleurErreur->afficher($message, $statut);
}
