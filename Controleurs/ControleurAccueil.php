<?php
declare(strict_types=1);

class ControleurAccueil
{
    private $vue;

    public function __construct(Vue $vue)
    {
        $this->vue = $vue;
    }

    public function index(): void
    {
        $this->vue->afficher('accueil', [
            'nomProjet' => 'ServiceCourt',
            'auteur' => 'Michael Matinyan',
            'versionPhp' => PHP_VERSION
        ], 'Accueil - ServiceCourt');
    }
}
