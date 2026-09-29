<?php ob_start(); ?>

<p><a href="index.php?action=utilisateur&id=<?= (int) $utilisateurId ?>">Retour au profil</a></p>

<h2>Confirmer l'annulation</h2>

<p>Êtes-vous sûr de vouloir annuler la réservation suivante ?</p>
<ul>
    <li><strong>Début :</strong> <?= htmlspecialchars($reservation['date_heure_debut'], ENT_QUOTES, 'UTF-8') ?></li>
    <li><strong>Fin :</strong> <?= htmlspecialchars($reservation['date_heure_fin'], ENT_QUOTES, 'UTF-8') ?></li>
</ul>

<form action="index.php?action=supprimer-reservation" method="post">
    <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= (int) $reservation['id'] ?>">
    <input type="hidden" name="utilisateur_id" value="<?= (int) $utilisateurId ?>">

    <button type="submit">Confirmer l'annulation</button>
</form>

<?php
$contenu = ob_get_clean();
$titrePage = 'Annuler la réservation';
require __DIR__ . '/../gabarit.php';
