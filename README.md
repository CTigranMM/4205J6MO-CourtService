# ServiceCourt

Application web transactionnelle permettant la gestion et la réservation de terrains de tennis. Elle aide les joueurs réguliers, les entraîneurs et les administrateurs de clubs à centraliser les disponibilités et éviter les conflits d'horaire.

## Prérequis

- **PHP** : Version 8.0 ou supérieure avec l'extension `pdo_mysql` activée.
- **Serveur Web** : Apache (intégré avec AMPPS, XAMPP, Laragon ou autonome).
- **Serveur de base de données** : MySQL 8.x / MariaDB 10.x.
- **Outil de gestion de base de données** : MySQL Workbench, phpMyAdmin ou client CLI MySQL.

---

## Reconstruction de la base de données

Les scripts SQL nécessaires à la création et au peuplement de la base de données se trouvent dans le dossier [`database/`](file:///c:/Projets/ServiceCourt/database) :

1. **Création du schéma et des tables** :
   Exécuter le script [`database/schema.sql`](file:///c:/Projets/ServiceCourt/database/schema.sql) pour créer le schéma `mydb` ainsi que l'ensemble des tables (`utilisateurs`, `abonnements`, `terrains`, `reservations`, `indisponibilites`) et leurs contraintes de clés étrangères.

2. **Peuplement des données initiales** :
   Exécuter le script [`database/ajout-10-lignes.sql`](file:///c:/Projets/ServiceCourt/database/ajout-10-lignes.sql) pour insérer le jeu d'essai initial (utilisateurs de test).

## Configuration PDO et Variables d'environnement

La connexion à la base de données est gérée dans [`config/bd.php`](file:///c:/Projets/ServiceCourt/config/bd.php) à l'aide de l'extension **PDO** de PHP avec gestion stricte des exceptions (`PDO::ERRMODE_EXCEPTION`) et mode de récupération associatif (`PDO::FETCH_ASSOC`).

Les informations de connexion sont récupérées via des **variables d'environnement système ou serveur**, sans valeurs secrètes versionnées dans le code source :

| Variable d'environnement | Description | Exemple |
| :--- | :--- | :--- |
| `DB_HOST` | Hôte du serveur de base de données | `localhost` ou `127.0.0.1` |
| `DB_PORT` | Port d'écoute de MySQL | `3306` |
| `DB_DATABASE` | Nom de la base de données | `mydb` |
| `DB_USERNAME` | Nom d'utilisateur de la base de données | *(votre identifiant local)* |
| `DB_PASSWORD` | Mot de passe de l'utilisateur de base de données | *(votre mot de passe local)* |

---

## Procédure d'installation et de démarrage

1. **Cloner ou placer le projet** :
   Placer le code source dans votre répertoire de travail (ex. `C:/Projets/ServiceCourt`).

2. **Reconstruire la base de données** :
   Exécuter `schema.sql` puis `ajout-10-lignes.sql` dans votre serveur MySQL local comme décrit ci-dessus.

3. **Configurer le serveur Apache (AMPPS)** :
   Ajouter la configuration de l'alias et la définition des variables d'environnement dans le fichier de configuration Apache (`httpd.conf` ou `vhosts.conf`), en adaptant les chemins physiques et vos identifiants locaux :

   ```apache
   Alias /ServiceCourt "C:/Projets/ServiceCourt"

   <Directory "C:/Projets/ServiceCourt">
       Options -Indexes +FollowSymLinks
       AllowOverride All
       Require all granted

       # Variables d'environnement pour la connexion PDO
       SetEnv DB_HOST "localhost"
       SetEnv DB_PORT "3306"
       SetEnv DB_DATABASE "mydb"
       SetEnv DB_USERNAME "votre_utilisateur"
       SetEnv DB_PASSWORD "votre_mot_de_passe"
   </Directory>
   ```

4. **Démarrer les services** :
   - Démarrer le serveur **MySQL** dans AMPPS.
   - Démarrer (ou redémarrer) le serveur **Apache** dans AMPPS.

5. **Accéder à l'application** :
   Ouvrir un navigateur web aux adresses suivantes :
   - **Accueil** : [http://localhost/ServiceCourt/index.php](http://localhost/ServiceCourt/index.php)
   - **Liste des utilisateurs** : [http://localhost/ServiceCourt/utilisateurs.php](http://localhost/ServiceCourt/utilisateurs.php)
   - **Récits utilisateurs** : [http://localhost/ServiceCourt/recits.php](http://localhost/ServiceCourt/recits.php)

---

## Compilation

Aucune étape de compilation n'est requise pour ce projet (PHP / CSS natifs).