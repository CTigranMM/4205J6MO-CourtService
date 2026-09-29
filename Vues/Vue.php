<?php

declare(strict_types=1);

class Vue
{
    public function afficher(string $fichier, array $donnees = [], string $titrePage = ''): void
    {
        $chemin = __DIR__ . '/' . $fichier . '.php';

        if (!is_file($chemin)) {
            throw new RuntimeException('Vue introuvable.');
        }

        extract($donnees, EXTR_SKIP);
        ob_start();
        require $chemin;
        $contenu = ob_get_clean();

        require __DIR__ . '/gabarit.php';
    }
}
