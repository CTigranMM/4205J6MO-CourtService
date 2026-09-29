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

    public function trouverParCourriel(string $courriel): ?array
    {
        $utilisateur = $this->executer(
            'SELECT id, nom, prenom, courriel, mot_de_passe, ROLE, date_creation
             FROM utilisateurs
             WHERE courriel = :courriel',
            ['courriel' => $courriel]
        )->fetch();

        return $utilisateur ?: null;
    }

    public function ajouter(array $donnees): void
    {
        $this->executer(
            'INSERT INTO utilisateurs (nom, prenom, courriel, mot_de_passe, ROLE, date_creation)
             VALUES (:nom, :prenom, :courriel, :mot_de_passe, :role, NOW())',
            [
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom'],
                'courriel' => $donnees['courriel'],
                'mot_de_passe' => $donnees['mot_de_passe'],
                'role' => 'MEMBRE'
            ]
        );
    }
}
