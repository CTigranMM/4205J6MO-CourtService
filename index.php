<?php
$nomProjet = 'ServiceCourt';
$auteur = 'Michael Matinyan';
$versionPhp = PHP_VERSION;

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($nomProjet) ?> - Accueil</title>
</head>
<body>
    <header>
        <h1>Bienvenue sur <?= htmlspecialchars($nomProjet) ?></h1>
        <p>Un projet réalisé par : <strong><?= htmlspecialchars($auteur) ?></strong></p>
        <p>Version de PHP active : <?= htmlspecialchars($versionPhp) ?></p>
    </header>

    <main>
        <section>
            <h2>À propos du projet</h2>
            <p>
                La gestion des réservations de terrains de tennis dans les clubs amateurs on souvant plusieurs erreur, conflits d'horaire et un manque de visibilité pour les membres. 
                <?= htmlspecialchars($nomProjet) ?> s'adresse aux joueurs réguliers, aux entraîneurs et aux administrateurs de clubs de tennis. 
                Cette application web centralise les disponibilités en temps réel, permet de réserver facilement un court et aide les administrateurs à mieux gérer l'utilisation des terrains.
            </p>
        </section>
        <ul>
            <li><a href="recits.php">recits</a></li>        
            <li>page?</li>        
        </ul>
    </main>
</body>
</html>