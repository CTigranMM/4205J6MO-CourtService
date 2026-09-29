<?php
declare(strict_types=1);

class ControleurErreur
{
    private $vue;

    public function __construct(Vue $vue)
    {
        $this->vue = $vue;
    }

    public function afficher(string $message, int $statut = 500): void
    {
        http_response_code($statut);
        $this->vue->afficher('erreur', ['messageErreur' => $message], 'Erreur - ServiceCourt');
    }
}
