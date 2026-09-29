<?php
declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class Reservation extends Modele
{


    public function obtenirReservationsParUtilisateur(int $utilisateurId): array
    {
        $requete = $this->pdo->prepare(
            'SELECT r.id, r.date_heure_debut, r.date_heure_fin, r.statut, t.nom_terrain
             FROM reservations r
             JOIN terrains t ON r.terrains_id = t.id
             WHERE r.utilisateur_id = :utilisateur_id
             ORDER BY r.date_heure_debut DESC'
        );
        $requete->execute(['utilisateur_id' => $utilisateurId]);

        return $requete->fetchAll();
    }

    public function ajouterReservation(array $donnees): void
    {
        $requete = $this->pdo->prepare(
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

    public function obtenirReservation(int $id): ?array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, date_heure_debut, date_heure_fin, statut, utilisateur_id, terrains_id
             FROM reservations
             WHERE id = :id'
        );
        $requete->execute(['id' => $id]);

        $reservation = $requete->fetch();
        return $reservation ?: null;
    }

    public function supprimerReservation(int $id): void
    {
        $requete = $this->pdo->prepare('DELETE FROM reservations WHERE id = :id');
        $requete->execute(['id' => $id]);
    }

}
