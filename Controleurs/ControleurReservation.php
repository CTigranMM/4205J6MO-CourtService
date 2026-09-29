<?php
declare(strict_types=1);

class ControleurReservation
{
    private $reservations;
    private $terrains;
    private $vue;
    private $erreurs;

    public function __construct(Reservation $reservations, Terrain $terrains, Vue $vue, ControleurErreur $erreurs)
    {
        $this->reservations = $reservations;
        $this->terrains = $terrains;
        $this->vue = $vue;
        $this->erreurs = $erreurs;
    }

    public function afficherFormulaireReservation(int $utilisateurId, array $donnees = [], array $erreursForm = []): void
    {
        $terrains = $this->terrains->obtenirTousLesTerrains();
        $this->vue->afficher('reservations/ajouter', [
            'terrains' => $terrains,
            'utilisateurId' => $utilisateurId,
            'donnees' => $donnees,
            'erreurs' => $erreursForm
        ], 'Ajouter réservation');
    }

    public function traiterAjoutReservation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->erreurs->afficher('Méthode non permise.', 405);
            return;
        }

        if (!verifierJetonCsrf($_POST['jeton_csrf'] ?? null)) {
            $this->erreurs->afficher('Jeton CSRF invalide.', 403);
            return;
        }

        $utilisateurId = filter_input(INPUT_POST, 'utilisateur_id', FILTER_VALIDATE_INT);
        if ($utilisateurId === false || $utilisateurId === null) {
            $this->erreurs->afficher('Identifiant utilisateur invalide.', 400);
            return;
        }

        $donnees = [
            'terrains_id' => trim($_POST['terrains_id'] ?? ''),
            'date_heure_debut' => trim($_POST['date_heure_debut'] ?? ''),
            'date_heure_fin' => trim($_POST['date_heure_fin'] ?? '')
        ];

        $erreursForm = [];

        if (empty($donnees['terrains_id']) || !is_numeric($donnees['terrains_id'])) {
            $erreursForm['terrains_id'] = 'Le terrain est requis et doit être valide.';
        }

        if (empty($donnees['date_heure_debut'])) {
            $erreursForm['date_heure_debut'] = 'La date de début est requise.';
        }

        if (empty($donnees['date_heure_fin'])) {
            $erreursForm['date_heure_fin'] = 'La date de fin est requise.';
        }

        if (!empty($erreursForm)) {
            $this->afficherFormulaireReservation($utilisateurId, $donnees, $erreursForm);
            return;
        }

        $this->reservations->ajouterReservation([
            'utilisateur_id' => $utilisateurId,
            'terrains_id' => (int) $donnees['terrains_id'],
            'date_heure_debut' => $donnees['date_heure_debut'],
            'date_heure_fin' => $donnees['date_heure_fin'],
            'statut' => 'confirmee'
        ]);

        header('Location: index.php?action=utilisateur&id=' . $utilisateurId);
        exit;
    }

    public function afficherConfirmationSuppressionReservation(int $id, int $utilisateurId): void
    {
        $reservation = $this->reservations->obtenirReservation($id);

        if ($reservation === null) {
            $this->erreurs->afficher('Réservation introuvable.', 404);
            return;
        }

        $this->vue->afficher('reservations/confirmer-suppression', [
            'reservation' => $reservation,
            'utilisateurId' => $utilisateurId,
            'id' => $id
        ], 'Confirmer suppression');
    }

    public function traiterSuppressionReservation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->erreurs->afficher('Méthode non permise.', 405);
            return;
        }

        if (!verifierJetonCsrf($_POST['jeton_csrf'] ?? null)) {
            $this->erreurs->afficher('Jeton CSRF invalide.', 403);
            return;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $utilisateurId = filter_input(INPUT_POST, 'utilisateur_id', FILTER_VALIDATE_INT);

        if ($id === false || $id === null || $utilisateurId === false || $utilisateurId === null) {
            $this->erreurs->afficher('Identifiant invalide.', 400);
            return;
        }

        $reservation = $this->reservations->obtenirReservation($id);
        if ($reservation === null) {
            $this->erreurs->afficher('Réservation introuvable.', 404);
            return;
        }

        $this->reservations->supprimerReservation($id);

        header('Location: index.php?action=utilisateur&id=' . $utilisateurId);
        exit;
    }
}
