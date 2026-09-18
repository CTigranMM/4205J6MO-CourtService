<section>
    <h2>Réservations</h2>

    <p>
        <a href="index.php?action=reservation-formulaire&utilisateur_id=<?= (int) $utilisateur['id'] ?>">
            Ajouter une réservation
        </a>
    </p>

    <?php if ($reservations === []): ?>
        <p>Aucune réservation.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Terrain</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['nom_terrain'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($reservation['date_heure_debut'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($reservation['date_heure_fin'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($reservation['statut'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="index.php?action=confirmer-suppression-reservation&id=<?= (int) $reservation['id'] ?>&utilisateur_id=<?= (int) $utilisateur['id'] ?>">
                                Annuler
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
