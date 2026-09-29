<?php
#
#declare(strict_types=1);
#
#class Routeur
#{
#    private $controleurArticle;
#    private $controleurCommentaire;
#    private $controleurErreur;
#
#    public function __construct(
#        ControleurAccueil $controleurAccueil,
#        ControleurReservation $controleurReservation,
#        ControleurRecits $controleurRecits,
#        ControleurUtilisateur $controleurUtilisateur,
#        ControleurErreur $controleurErreur
#    ) {
#        $this->controleurAccueil = $controleurAccueil;
#        $this->controleurReservation = $controleurReservation;
#        $this->controleurRecits = $controleurRecits;
#        $this->controleurUtilisateur = $controleurUtilisateur;
#        $this->controleurErreur = $controleurErreur;
#    }
#
#    public function router(): void
#    {
#        $action = $_GET['action'] ?? 'articles';
#
#        switch ($action) {
#            case 'articles':
#                $this->controleurArticle->index();
#                break;
#
#            case 'article':
#                $this->controleurArticle->afficher($this->lireIdGet());
#                break;
#
#            case 'commentaire-ajouter':
#                $this->exigerEcriture($_POST['jeton_csrf'] ?? null);
#                $this->controleurCommentaire->ajouter($_POST);
#                break;
#
#            default:
#                $this->controleurErreur->afficher('Page introuvable.', 404);
#        }
#    }
#
#    private function lireIdGet(): int
#    {
#        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
#
#        if ($id === false || $id === null) {
#            throw new InvalidArgumentException('Identifiant invalide.', 400);
#        }
#
#        return $id;
#    }
#
#    private function exigerEcriture($jeton): void
#    {
#        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
#            throw new RuntimeException('Méthode non permise.', 405);
#        }
#
#        if (!verifierJetonCsrf($jeton)) {
#            throw new RuntimeException('Requête refusée.', 403);
#        }
#    }
#}
#