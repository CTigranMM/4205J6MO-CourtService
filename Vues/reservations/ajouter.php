<?php ob_start(); ?>

<p><a href="index.php?action=utilisateur&id=<?= (int) $utilisateurId ?>">Retour au profil</a></p>

<h2>Ajouter une réservation pour l'utilisateur #<?= (int) $utilisateurId ?></h2>

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

<form action="index.php?action=reservation-ajouter" method="post">
    <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars(genererJetonCSRF(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="utilisateur_id" value="<?= (int) $utilisateurId ?>">

    <div>
        <label for="terrains_id">Terrain :</label>
        <select id="terrains_id" name="terrains_id" required>
            <option value="">-- Choisir un terrain --</option>
            <?php foreach ($terrains as $terrain): ?>
                <option value="<?= (int) $terrain['id'] ?>" <?= ($donnees['terrains_id'] ?? '') == (string) $terrain['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($terrain['nom_terrain'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="date_heure_debut">Date et heure de début :</label>
        <input type="datetime-local" id="date_heure_debut" name="date_heure_debut" 
               value="<?= htmlspecialchars($donnees['date_heure_debut'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div>
        <label for="date_heure_fin">Date et heure de fin :</label>
        <input type="datetime-local" id="date_heure_fin" name="date_heure_fin" 
               value="<?= htmlspecialchars($donnees['date_heure_fin'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div>
        <button type="submit">Ajouter la réservation</button>
    </div>
</form>

<?php
$contenu = ob_get_clean();
$titre = 'Ajouter une réservation';
require __DIR__ . '/../gabarit.php';
