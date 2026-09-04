<?php
declare(strict_types=1);
function obtenirTousLesUtilisateurs(PDO $pdo): array {
    try {
        $requete = $pdo->prepare(
            'SELECT id, nom, prenom, courriel, ROLE, date_creation 
             FROM utilisateurs 
             ORDER BY id DESC'
        );
        
        $requete->execute();
        
        return $requete->fetchAll();

    } catch (Throwable $exception) {
        error_log($exception->getMessage());
        return [];
    }
}
?>