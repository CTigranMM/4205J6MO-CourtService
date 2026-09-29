<h1>Créer un compte</h1>

<?php if (!empty($erreurs)): ?>
    <div role="alert">
        <p>Veuillez corriger les erreurs suivantes :</p>
        <ul>
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="index.php?action=inscription-traiter" method="post">
    <input type="hidden" name="jeton_csrf"
           value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">

    <label for="prenom">Prénom</label>
    <input id="prenom" name="prenom"
           value="<?= htmlspecialchars($donnees['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    
    <label for="nom">Nom</label>
    <input id="nom" name="nom"
           value="<?= htmlspecialchars($donnees['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

    <label for="courriel">Courriel</label>
    <input type="email" id="courriel" name="courriel"
           value="<?= htmlspecialchars($donnees['courriel'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

    <label for="mot_de_passe">Mot de passe</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>

    <button type="submit">S'inscrire</button>
</form>
