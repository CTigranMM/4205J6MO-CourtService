<?php

declare(strict_types=1);

class Routeur
{
    private $controleurAccueil;
    private $controleurRecits;
    private $controleurUtilisateur;
    private $controleurReservation;
    private $controleurErreur;

    public function __construct(
        ControleurAccueil $controleurAccueil,
        ControleurRecits $controleurRecits,
        ControleurUtilisateur $controleurUtilisateur,
        ControleurReservation $controleurReservation,
        ControleurErreur $controleurErreur
    ) {
        $this->controleurAccueil = $controleurAccueil;
        $this->controleurRecits = $controleurRecits;
        $this->controleurUtilisateur = $controleurUtilisateur;
        $this->controleurReservation = $controleurReservation;
        $this->controleurErreur = $controleurErreur;
    }

    public function router(): void
    {
        $action = $_GET['action'] ?? 'accueil';

        switch ($action) {
            case 'accueil':
                $this->controleurAccueil->index();
                break;
            case 'recits':
                $this->controleurRecits->index();
                break;
            case 'utilisateurs':
                $this->controleurUtilisateur->index();
                break;
            case 'utilisateur':
                $this->controleurUtilisateur->afficher($this->lireIdGet('id'));
                break;
            case 'inscription':
                $this->controleurUtilisateur->afficherInscription();
                break;
            case 'inscription-traiter':
                $this->exigerEcriture($_POST['jeton_csrf'] ?? null);
                $this->controleurUtilisateur->traiterInscription();
                break;
            case 'connexion':
                $this->controleurUtilisateur->afficherConnexion();
                break;
            case 'authentifier':
                $this->exigerEcriture($_POST['jeton_csrf'] ?? null);
                $this->controleurUtilisateur->traiterConnexion();
                break;
            case 'deconnexion':
                $this->exigerEcriture($_POST['jeton_csrf'] ?? null);
                $this->controleurUtilisateur->deconnecter();
                break;
            case 'reservation-formulaire':
                $this->controleurReservation->afficherFormulaireReservation($this->lireIdGet('utilisateur_id'));
                break;
            case 'reservation-ajouter':
                $this->exigerEcriture($_POST['jeton_csrf'] ?? null);
                $this->controleurReservation->traiterAjoutReservation();
                break;
            case 'confirmer-suppression-reservation':
                $id = $this->lireIdGet('id');
                $utilisateurId = $this->lireIdGet('utilisateur_id');
                $this->controleurReservation->afficherConfirmationSuppressionReservation($id, $utilisateurId);
                break;
            case 'supprimer-reservation':
                $this->exigerEcriture($_POST['jeton_csrf'] ?? null);
                $this->controleurReservation->traiterSuppressionReservation();
                break;
            default:
                throw new InvalidArgumentException('Page introuvable.', 404);
        }
    }

    private function lireIdGet(string $param = 'id'): int
    {
        $id = filter_input(INPUT_GET, $param, FILTER_VALIDATE_INT);

        if ($id === false || $id === null) {
            throw new InvalidArgumentException('Identifiant invalide.', 400);
        }

        return $id;
    }

    private function lireIdPost(string $param = 'id'): int
    {
        $id = filter_input(INPUT_POST, $param, FILTER_VALIDATE_INT);

        if ($id === false || $id === null) {
            throw new InvalidArgumentException('Identifiant invalide.', 400);
        }

        return $id;
    }

    private function exigerEcriture($jeton): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new RuntimeException('Méthode non permise.', 405);
        }

        if (!verifierJetonCsrf($jeton)) {
            throw new RuntimeException('Requête refusée.', 403);
        }
    }
}