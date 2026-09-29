<?php
$nomProjet = 'ServiceCourt';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titrePage ?? $nomProjet ?? 'ServiceCourt', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>ServiceCourt</h1>
        <nav>
            <ul>
                <li><a href="index.php?action=accueil">Accueil</a></li>
                <li><a href="index.php?action=recits">Récits</a></li>
                <li><a href="index.php?action=utilisateurs">Utilisateurs</a></li>
                
                <?php $user = $utilisateurConnecte ?? $_SESSION['utilisateur'] ?? null; ?>
                <?php if ($user): ?>
                    <li>Bonjour :  <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom'], ENT_QUOTES, 'UTF-8') ?></li>
                    <li><a href="index.php?action=deconnexion">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=inscription">Inscription</a></li>
                    <li><a href="index.php?action=connexion">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?= $contenu ?? '' ?>
    </main>
</body>
</html>