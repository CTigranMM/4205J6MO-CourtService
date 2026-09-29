<?php

declare(strict_types=1);

abstract class Modele
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function executer(string $sql, array $parametres = []): PDOStatement
    {
        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);

        return $requete;
    }
}
