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
        <div class="nav-container">
            <a href="index.php?action=accueil" class="nav-brand">ServiceCourt</a>
        <nav>
            <ul>
                <li><a href="index.php?action=accueil">Accueil</a></li>
                <li><a href="index.php?action=recits">Récits</a></li>
                <li><a href="index.php?action=utilisateurs">Utilisateurs</a></li>
                
                <?php $user = $utilisateurConnecte ?? $_SESSION['utilisateur'] ?? null; ?>
                <?php if ($user): ?>
                    <li><span class="nav-text">Bonjour : <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom'], ENT_QUOTES, 'UTF-8') ?></span></li>
                    <li>
                        <form action="index.php?action=deconnexion" method="post" style="display:inline;">
                            <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="nav-btn">Déconnexion</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li><a href="index.php?action=inscription">Inscription</a></li>
                    <li><a href="index.php?action=connexion">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        </div>
    </header>

    <main>
        <?= $contenu ?? '' ?>
    </main>
</body>
</html>