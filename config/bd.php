<?php

declare(strict_types=1);

function lireVariable(string $nom): string
{
    $valeur = getenv($nom);

    if ($valeur === false || $valeur === '') {
        throw new RuntimeException(
            "Variable d'environnement manquante : {$nom}"
        );
    }

    return $valeur;
}

$hote = lireVariable('DB_HOST');
$port = lireVariable('DB_PORT');
$nomBD = lireVariable('DB_DATABASE');
$utilisateur = lireVariable('DB_USERNAME');
$motDePasse = lireVariable('DB_PASSWORD');

$dsn = "mysql:host={$hote};port={$port};dbname={$nomBD};charset=utf8mb4";

$pdo = new PDO($dsn, $utilisateur, $motDePasse, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);