<?php ob_start(); ?>
<h2>Erreur</h2>
<p><?= htmlspecialchars($message ?? 'Une erreur est survenue.', ENT_QUOTES, 'UTF-8') ?></p>
<?php
$contenu = ob_get_clean();
$titre = 'Erreur - ServiceCourt';
require __DIR__ . '/gabarit.php';

