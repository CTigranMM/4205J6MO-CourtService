<?php
declare(strict_types=1);

class ControleurUtilisateur
{
    private $utilisateurs;
    private $reservations;
    private $vue;
    private $erreurs;
    private $auth;

    public function __construct(Utilisateur $utilisateurs, Reservation $reservations, Vue $vue, ControleurErreur $erreurs, Authentification $auth)
    {
        $this->utilisateurs = $utilisateurs;
        $this->reservations = $reservations;
        $this->vue = $vue;
        $this->erreurs = $erreurs;
        $this->auth = $auth;
    }

    public function index(): void
    {
        $this->vue->afficher('utilisateurs/index', [
            'nomProjet' => 'ServiceCourt',
            'utilisateurs' => $this->utilisateurs->obtenirTousLesUtilisateurs(),
            'utilisateurConnecte' => $this->auth->utilisateur()
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
            'reservations' => $reservations,
            'utilisateurConnecte' => $this->auth->utilisateur()
        ], $utilisateur['prenom'] . ' ' . $utilisateur['nom']);
    }

    public function afficherInscription(array $erreursForm = [], array $donnees = []): void
    {
        $this->vue->afficher('utilisateurs/inscription', [
            'erreurs' => $erreursForm,
            'donnees' => $donnees,
            'utilisateurConnecte' => $this->auth->utilisateur()
        ], 'Créer un compte - ServiceCourt');
    }

    public function traiterInscription(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->erreurs->afficher('Méthode non permise.', 405);
            return;
        }

        if (!verifierJetonCsrf($_POST['jeton_csrf'] ?? null)) {
            $this->erreurs->afficher('Jeton CSRF invalide.', 403);
            return;
        }

        $donnees = [
            'prenom' => trim($_POST['prenom'] ?? ''),
            'nom' => trim($_POST['nom'] ?? ''),
            'courriel' => trim($_POST['courriel'] ?? ''),
            'mot_de_passe' => $_POST['mot_de_passe'] ?? ''
        ];

        $erreursForm = [];

        if (empty($donnees['prenom'])) {
            $erreursForm['prenom'] = 'Le prénom est requis.';
        }

        if (empty($donnees['nom'])) {
            $erreursForm['nom'] = 'Le nom est requis.';
        }

        if (empty($donnees['courriel']) || !filter_var($donnees['courriel'], FILTER_VALIDATE_EMAIL)) {
            $erreursForm['courriel'] = 'Une adresse courriel valide est requise.';
        } elseif ($this->utilisateurs->trouverParCourriel($donnees['courriel'])) {
            $erreursForm['courriel'] = 'Cette adresse courriel est déjà utilisée.';
        }

        if (empty($donnees['mot_de_passe']) || strlen($donnees['mot_de_passe']) < 8) {
            $erreursForm['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if (!empty($erreursForm)) {
            $this->afficherInscription($erreursForm, $donnees);
            return;
        }

        $donnees['mot_de_passe'] = password_hash($donnees['mot_de_passe'], PASSWORD_DEFAULT);

        $this->utilisateurs->ajouter($donnees);

        $utilisateur = $this->utilisateurs->trouverParCourriel($donnees['courriel']);
        $this->auth->connecter($utilisateur);

        header('Location: index.php');
        exit;
    }

    public function afficherConnexion(string $erreur = null, string $courriel = ''): void
    {
        $this->vue->afficher('utilisateurs/connexion', [
            'erreur' => $erreur,
            'courriel' => $courriel,
            'utilisateurConnecte' => $this->auth->utilisateur()
        ], 'Connexion - ServiceCourt');
    }

    public function traiterConnexion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->erreurs->afficher('Méthode non permise.', 405);
            return;
        }

        if (!verifierJetonCsrf($_POST['jeton_csrf'] ?? null)) {
            $this->erreurs->afficher('Jeton CSRF invalide.', 403);
            return;
        }

        $courriel = trim($_POST['courriel'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $utilisateur = $this->utilisateurs->trouverParCourriel($courriel);

        if (!$utilisateur) {
            $this->afficherConnexion('Aucun compte n\'est associé à ce courriel.', $courriel);
            return;
        }

        if (password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            $this->auth->connecter($utilisateur);
            header('Location: index.php');
            exit;
        }

        $this->afficherConnexion('Mot de passe incorrect.', $courriel);
    }

    public function deconnecter(): void
    {
        $this->auth->deconnecter();
        header('Location: index.php');
        exit;
    }
}
