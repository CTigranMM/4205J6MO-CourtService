<?php ob_start(); ?>
<section>
    <h2>Bienvenue sur <?= htmlspecialchars($nomProjet, ENT_QUOTES, 'UTF-8') ?></h2>
    <p>Un projet réalisé par : <strong><?= htmlspecialchars($auteur, ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p>Version de PHP active : <?= htmlspecialchars($versionPhp, ENT_QUOTES, 'UTF-8') ?></p>
</section>

<section>
    <h2>À propos du projet</h2>
    <p>
        La gestion des réservations de terrains de tennis dans les clubs amateurs on souvant plusieurs erreur, conflits d'horaire et un manque de visibilité pour les membres. 
        <?= htmlspecialchars($nomProjet, ENT_QUOTES, 'UTF-8') ?> s'adresse aux joueurs réguliers, aux entraîneurs et aux administrateurs de clubs de tennis. 
        Cette application web centralise les disponibilités en temps réel, permet de réserver facilement un court et aide les administrateurs à mieux gérer l'utilisation des terrains.
    </p>
</section>
<?php
$contenu = ob_get_clean();
$titre = $nomProjet . ' - Accueil';
require __DIR__ . '/gabarit.php';
