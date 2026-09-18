<?php ob_start(); ?>

<p><a href="index.php?action=utilisateurs">Retour aux utilisateurs</a></p>

<article>
    <h1><?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p><strong>Courriel:</strong> <?= htmlspecialchars($utilisateur['courriel'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Rôle:</strong> <?= htmlspecialchars($utilisateur['ROLE'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Membre depuis:</strong> <?= htmlspecialchars($utilisateur['date_creation'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
</article>

<?php require __DIR__ . '/../reservations/liste.php'; ?>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../gabarit.php';
