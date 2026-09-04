<?php
$nomProjet = 'ServiceCourt';
?>
<?php ob_start(); ?>

<h1><?= htmlspecialchars($nomProjet, ENT_QUOTES, 'UTF-8') ?></h1>

<?php if ($articles === []): ?>
    <p>Aucun article n'est disponible.</p>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <article>
            <h2><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p><strong><?= htmlspecialchars($article['resume'], ENT_QUOTES, 'UTF-8') ?></strong></p>
            <p><?= nl2br(htmlspecialchars($article['contenu'], ENT_QUOTES, 'UTF-8')) ?></p>
            <small>
                Publié le
                <?= htmlspecialchars($article['date_publie'], ENT_QUOTES, 'UTF-8') ?>
            </small>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../gabarit.php';