# ServiceCourt

Application web transactionnelle permettant la gestion et la réservation de terrains de tennis. Elle aide les joueurs réguliers, les entraîneurs et les administrateurs de clubs à centraliser les disponibilités et éviter les conflits d'horaire.

## Prérequis

- AMPPS
- PHP (version compatible AMPPS)
- MySQL

## Installation

1. Récupérer le dépôt.
2. Importer le fichier SQL.
3. Adapter la configuration locale sans versionner les secrets.

## Démarrage

Ajouter le bloc suivant dans le fichier de configuration Apache utilisé par AMPPS, en adaptant le chemin physique :

```apache
Alias /ServiceCourt "C:/Projets/ServiceCourt"

<Directory "C:/Projets/ServiceCourt">
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

Redémarrer ensuite Apache et démarrer MySQL dans AMPPS, puis ouvrir :

```text
http://localhost/ServiceCourt/index.php
```

## Compilation

Aucune compilation n'est nécessaire pour cette version.