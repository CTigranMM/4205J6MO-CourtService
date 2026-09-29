<?php
declare(strict_types=1);

class ControleurUtilisateur
{
    private $utilisateurs;
    private $reservations;
    private $vue;
    private $erreurs;

    public function __construct(Utilisateur $utilisateurs, Reservation $reservations, Vue $vue, ControleurErreur $erreurs)
    {
        $this->utilisateurs = $utilisateurs;
        $this->reservations = $reservations;
        $this->vue = $vue;
        $this->erreurs = $erreurs;
    }

    public function index(): void
    {
        $this->vue->afficher('utilisateurs/index', [
            'nomProjet' => 'ServiceCourt',
            'utilisateurs' => $this->utilisateurs->obtenirTousLesUtilisateurs()
        ], 'Liste des utilisateurs - ServiceCourt');
    }

    public function afficher(int $id): void
    {
        $utilisateur = $this->utilisateurs->obtenirUtilisateur($id);

        if ($utilisateur === null) {
            $this->erreurs->afficher('Utilisateur introuvable.', 404);
            return;
        }

        $reservations = $this->reservations->obtenirReservationsParUtilisateur($id);
        
        $this->vue->afficher('utilisateurs/afficher', [
            'nomProjet' => 'ServiceCourt',
            'utilisateur' => $utilisateur,
            'reservations' => $reservations
        ], $utilisateur['prenom'] . ' ' . $utilisateur['nom']);
    }
}
