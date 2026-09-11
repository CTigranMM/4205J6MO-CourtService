<?php ob_start(); ?>

<h1>Utilisateurs de <?= htmlspecialchars($nomProjet, ENT_QUOTES, 'UTF-8') ?></h1>

<?php if (empty($utilisateurs)): ?>
    <p>Aucun utilisateur n'est disponible.</p>
<?php else: ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Courriel</th>
                <th>Rôle</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $utilisateur['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['courriel'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['ROLE'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['date_creation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
$contenu = ob_get_clean();
$titre = "Utilisateurs - " . $nomProjet;
require __DIR__ . '/../gabarit.php';
