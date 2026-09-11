<?php
$nomProjet = 'ServiceCourt';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($nomProjet, ENT_QUOTES, 'UTF-8') ?></title>
    <title><?= htmlspecialchars($titre ?? 'ServiceCourt', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>ServiceCourt</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="recits.php">Récits</a></li>
                <li><a href="utilisateurs.php">Utilisateurs</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?= $contenu ?? '' ?>
    </main>
</body>
</html>