<?php
declare(strict_types=1);

require_once __DIR__ . '/../Modeles/utilisateurs-modele.php';
require_once __DIR__ . '/../Modeles/reservations-modele.php';

function afficherListeUtilisateurs(PDO $pdo): void
{
    $nomProjet = 'ServiceCourt';
    $titre = 'Liste des utilisateurs - ' . $nomProjet;
    $utilisateurs = obtenirTousLesUtilisateurs($pdo);
    require_once __DIR__ . '/../Vues/utilisateurs/index.php';
}

function afficherUtilisateur(PDO $pdo, int $id): void
{
    $nomProjet = 'ServiceCourt';
    $utilisateur = obtenirUtilisateur($pdo, $id);

    if ($utilisateur === null) {
        afficherErreur('Utilisateur introuvable.', 404);
        return;
    }

    $reservations = obtenirReservationsParUtilisateur($pdo, $id);
    $titrePage = $utilisateur['prenom'] . ' ' . $utilisateur['nom'];

    require __DIR__ . '/../Vues/utilisateurs/afficher.php';
}