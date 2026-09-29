<?php

declare(strict_types=1);

function lireVariable(string $nom, string $defaut = null): string
{
    $valeur = getenv($nom);

    if ($valeur === false || $valeur === '') {
        if ($defaut !== null) {
            return $defaut;
        }
        throw new RuntimeException(
            "Variable d'environnement manquante : {$nom}"
        );
    }

    return $valeur;
}

$hote = lireVariable('DB_HOST', '127.0.0.1');
$port = lireVariable('DB_PORT', '3306');
$nomBD = lireVariable('DB_DATABASE', 'mydb');
$utilisateur = lireVariable('DB_USERNAME', 'root');
$motDePasse = lireVariable('DB_PASSWORD', ''); // Souvent vide en local

$dsn = "mysql:host={$hote};port={$port};dbname={$nomBD};charset=utf8mb4";

$pdo = new PDO($dsn, $utilisateur, $motDePasse, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);