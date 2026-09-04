<?php
$nomProjet = 'ServiceCourt';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récits utilisateurs - <?= htmlspecialchars($nomProjet) ?></title>
</head>
<body>
    <header>
        <h1>Récits utilisateurs et critères d'acceptation</h1>
        <nav>
            <a href="index.php">index</a>
        </nav>
    </header>

    <main>
        <ul>
            <li>
                Récit 1 : Comme joueur, je veux consulter les terrains de tennis disponibles à une date précise afin de planifier mon match.
                <ul>
                    <li>Critère A : Le système affiche un calendrier permettant de sélectionner une date.</li>
                    <li>Critère B : Seuls les terrains sans réservation existante pour la date choisie s'affichent comme « Disponibles ».</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 2 : Comme joueur, je veux réserver un terrain disponible sur une horaire spécifique afin de garantir ma place au club.
                <ul>
                    <li>Critère A : Le système empêche la validation de la réservation si une autre réservation existante.</li>
                    <li>Critère B : Un message de confirmation de réservation s'affiche à l'écran après la soumission réussie.</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 3 : Comme joueur, je veux pouvoir annuler ma réservation au moins 24 heures à l'avance afin de libérer le terrain pour d'autres membres.
                <ul>
                    <li>Critère A : Le bouton d'annulation est désactivé ou masqué si la réservation a lieu dans moins de 24 heures.</li>
                    <li>Critère B : L'annulation met immédiatement à jour le statut du terrain concerné à « Disponible » dans le calendrier.</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 4 : Comme entraîneur, je veux réserver un terrain pour une session sans devoir réserver manuellement chaque semaine.
                <ul>
                    <li>Critère A : Une option « Répéter chaque semaine » est visible lors de la création de la réservation.</li>
                    <li>Critère B : Le système vérifie et confirme la disponibilité pour toutes les occurrences sur une période d'un mois avant de valider.</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 5 : Comme administrateur, je veux pouvoir bloquer un terrain spécifique afin d'empêcher les réservations pendant des travaux de maintenance.
                <ul>
                    <li>Critère A : Un statut « En maintenance » est appliqué à n'importe quel terrain depuis le panneau d'administration.</li>
                    <li>Critère B : Un terrain en maintenance disparaît complètement des disponibilités visibles pour les joueurs.</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 6 : Comme membre, je veux modifier mes informations de profil (courriel) afin que le club puisse me contacter en cas de changement d'horaire d'urgence.
                <ul>
                    <li>Critère A : Les champs du formulaire de modification sont pré-remplis avec les données actuelles de la base de données.</li>
                    <li>Critère B : Les modifications sont appliqué dans la base de données uniquement si le format de l'adresse courriel est valide.</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 7 : Comme administrateur, je veux consulter la liste de toutes les réservations de la journée afin de préparer l'accueil et la vérification des présences.
                <ul>
                    <li>Critère A : Le tableau de bord affiche toutes les réservations du jour, triées par ordre chronologique.</li>
                    <li>Critère B : Chaque ligne du tableau indique clairement le nom du joueur principal, sont address courielle, le numéro du terrain et l'heure de début.</li>
                </ul>
            </li>
            <br>
            <li>
                Récit 8 : Comme joueur, je veux voir l'historique complet de mes réservations passées afin de suivre ma fréquence de jeu.
                <ul>
                    <li>Critère A : Une section « Historique » liste toutes les réservations dont la date est strictement antérieure à la date actuelle.</li>
                    <li>Critère B : Les réservations qui ont été annulées dans le passé sont distinguées visuellement (ex: texte barré, mention « Annulée »).</li>
                </ul>
            </li>
        </ul>
    </main>
</body>
</html>