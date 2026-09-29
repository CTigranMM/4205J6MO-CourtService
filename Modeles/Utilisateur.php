<?php
declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class Utilisateur extends Modele
{

    public function obtenirTousLesUtilisateurs(): array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, prenom, courriel, ROLE, date_creation 
             FROM utilisateurs 
             ORDER BY id DESC'
        );
    
        $requete->execute();
    
        return $requete->fetchAll();
    }

    public function obtenirUtilisateur(int $id): ?array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, prenom, courriel, ROLE, date_creation 
             FROM utilisateurs 
             WHERE id = :id'
        );
        $requete->execute(['id' => $id]);

        $utilisateur = $requete->fetch();

        return $utilisateur ?: null;
    }

}
