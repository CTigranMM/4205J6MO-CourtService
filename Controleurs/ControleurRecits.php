<?php
declare(strict_types=1);

class ControleurRecits
{
    private $vue;

    public function __construct(Vue $vue)
    {
        $this->vue = $vue;
    }

    public function index(): void
    {
        $this->vue->afficher('recits', [
            'nomProjet' => 'ServiceCourt'
        ], 'Récits - ServiceCourt');
    }
}
