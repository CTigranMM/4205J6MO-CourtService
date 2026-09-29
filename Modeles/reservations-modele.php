<?php
declare(strict_types=1);

function obtenirReservationsParUtilisateur(PDO $pdo, int $utilisateurId): array
{
    $requete = $pdo->prepare(
        'SELECT r.id, r.date_heure_debut, r.date_heure_fin, r.statut, t.nom_terrain
         FROM reservations r
         JOIN terrains t ON r.terrains_id = t.id
         WHERE r.utilisateur_id = :utilisateur_id
         ORDER BY r.date_heure_debut DESC'
    );
    $requete->execute(['utilisateur_id' => $utilisateurId]);

    return $requete->fetchAll();
}

function ajouterReservation(PDO $pdo, array $donnees): void
{
    $requete = $pdo->prepare(
        'INSERT INTO reservations (terrains_id, date_heure_debut, date_heure_fin, statut, utilisateur_id)
         VALUES (:terrains_id, :date_heure_debut, :date_heure_fin, :statut, :utilisateur_id)'
    );
    
    $requete->execute([
        'terrains_id' => $donnees['terrains_id'],
        'date_heure_debut' => $donnees['date_heure_debut'],
        'date_heure_fin' => $donnees['date_heure_fin'],
        'statut' => $donnees['statut'],
        'utilisateur_id' => $donnees['utilisateur_id']
    ]);
}

function obtenirReservation(PDO $pdo, int $id): ?array
{
    $requete = $pdo->prepare(
        'SELECT id, date_heure_debut, date_heure_fin, statut, utilisateur_id, terrains_id
         FROM reservations
         WHERE id = :id'
    );
    $requete->execute(['id' => $id]);

    $reservation = $requete->fetch();
    return $reservation ?: null;
}

function supprimerReservation(PDO $pdo, int $id): void
{
    $requete = $pdo->prepare('DELETE FROM reservations WHERE id = :id');
    $requete->execute(['id' => $id]);
}
