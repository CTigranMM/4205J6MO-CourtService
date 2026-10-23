# ServiceCourt 

Application web transactionnelle permettant la gestion et la réservation de terrains de tennis pour joueurs, entraîneurs et administrateurs d'un club sportif.

---

## Récits utilisateurs et critères d'acceptation (Chapitre 07)

### Récits reconstruits et complétés cette semaine :

* **Récit 2 : Réserver un terrain (Création & Consultation)** — **Complété**
  * *En tant que joueur, je veux réserver un terrain disponible afin de garantir ma place au club.*
  * **Critère A** : Formulaire de création de réservation avec validation serveur obligatoire (`EnregistrerReservationRequest`) : terrain existant, date et heure valides, date de fin obligatoirement postérieure au début.
  * **Critère B** : Redirection vers la vue détail et affichage d'un message flash de confirmation (`session('status')`) après soumission réussie.
  * **Critère C** : Consultation sous forme de liste paginée (`/reservations`) et page de détail (`/reservations/{id}`) avec chargement hâtif (*Eager Loading* `with(['terrain', 'user'])`).

* **Récit 3 : Modifier et Annuler / Supprimer une réservation (CRUD complet)** — **Complété**
  * *En tant que joueur, je veux pouvoir modifier mes horaires ou supprimer ma réservation afin de libérer le court pour d'autres membres.*
  * **Critère A** : Formulaire de modification (`/reservations/{id}/edit`) prérempli avec les données existantes de la réservation et conservation de la saisie (`old()`) en cas d'erreur de validation.
  * **Critère B** : Suppression sécurisée de la réservation via une requête HTTP `DELETE` avec protection CSRF (`@csrf` + `@method('DELETE')`), sans jamais utiliser `GET`.
  * **Critère C** : Prise en charge des cas limites (réservations à moins de 24h, réservations passées et réservations déjà annulées) préparés et vérifiables dans le seeder.

### Autres récits du domaine (étapes ultérieures) :
* **Récit 1** : Comme joueur, je veux consulter les terrains de tennis disponibles à une date précise afin de planifier mon match.
* **Récit 4** : Comme entraîneur, je veux réserver un terrain pour une session récurrente sans devoir réserver manuellement chaque semaine.
* **Récit 5** : Comme administrateur, je veux pouvoir bloquer un terrain spécifique afin d'empêcher les réservations pendant des travaux de maintenance.
* **Récit 6** : Comme membre, je veux modifier mes informations de profil (courriel) afin que le club puisse me contacter.
* **Récit 7** : Comme administrateur, je veux consulter la liste de toutes les réservations de la journée afin de préparer l'accueil.
* **Récit 8** : Comme joueur, je veux voir l'historique complet de mes réservations passées afin de suivre ma fréquence de jeu.

---

## Installation et reconstruction de la base locale jetable

### 1. Démarrer les services Docker
Assurez-vous que Docker Desktop est démarré, puis lancez les conteneurs :
```powershell
docker compose up -d
```

### 2. Reconstruire et peupler la base de données
Exécutez la commande suivante depuis votre terminal hôte PowerShell pour réinitialiser la base locale et charger le jeu d'essai :
```powershell
docker compose exec app php artisan migrate:fresh --seed
```

### 3. Données de démonstration et cas limites
Le seeder ([DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)) utilise l'enchaînement fluide des factories et génère :
* **1 utilisateur principal pour les tests de démonstration** :
  * **Courriel** : `joueur@servicecourt.ca`
  * **Mot de passe** : `password`
* **4 terrains de tennis réalistes** : Philippe-Chatrier (terre battue), Arthur Ashe (dur), Centre Court (gazon synthétique) et Court Entraînement.
* **4 cas limites explicitement préparés** :
  1. **Terrain vide** (*Court Entraînement*) : aucun enregistrement lié, permettant de valider l'affichage du cas vide (`@forelse ... @empty`).
  2. **Réservation à moins de 24h** : réservation prévue aujourd'hui dans 2 heures (permettant d'évaluer la règle d'interdiction d'annulation à court préavis).
  3. **Réservation passée** : réservation hier de 10h à 11h (historique non modifiable).
  4. **Réservation annulée** : réservation portant le statut `annulee` (distinction visuelle).

---

## Routes principales de l'application

Toutes les opérations CRUD sont déclarées via un contrôleur de ressources (`Route::resource('reservations', ReservationController::class)`) :

| Méthode HTTP | URL | Nom de la route | Contrôleur & Action | Description / Rôle |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | - | Redirection | Redirige automatiquement vers `/reservations` |
| `GET` | `/reservations` | `reservations.index` | `ReservationController@index` | Liste paginée de toutes les réservations (Eager loading) |
| `GET` | `/reservations/create` | `reservations.create` | `ReservationController@create` | Formulaire d'ajout d'une nouvelle réservation |
| `POST` | `/reservations` | `reservations.store` | `ReservationController@store` | Validation (Form Request) et insertion avec message flash |
| `GET` | `/reservations/{reservation}` | `reservations.show` | `ReservationController@show` | Fiche détaillée d'une réservation (404 si introuvable) |
| `GET` | `/reservations/{reservation}/edit` | `reservations.edit` | `ReservationController@edit` | Formulaire prérempli pour modifier une réservation |
| `PATCH` / `PUT` | `/reservations/{reservation}` | `reservations.update` | `ReservationController@update` | Validation et mise à jour de la réservation |
| `DELETE` | `/reservations/{reservation}` | `reservations.destroy` | `ReservationController@destroy` | Suppression sécurisée (formulaire POST + `@method('DELETE')`) |

---

## Décision concernant les transactions

Pour les opérations CRUD actuelles sur la ressource `Reservation`, chaque opération (création, mise à jour, suppression) n'effectue qu'**une seule écriture SQL atomique** dans la table `reservations`. 

Conformément aux principes de la section 7.4 et 7.7 du cours, une transaction `DB::transaction()` n'est nécessaire que si deux écritures dépendantes doivent réussir ou échouer ensemble. Une transaction sera introduite dans une étape ultérieure lorsque le domaine combinera deux écritures inséparables (par exemple : déduire un crédit sur un forfait d'abonnement ET enregistrer la réservation).

---

## Exécution des tests automatisés

La totalité du CRUD (consultation, formulaires, validation côté serveur, préremplissage, redirection, flash messages et suppression) est couverte par une suite de tests Feature :

```powershell
docker compose exec app php artisan test
```

Résultat des tests :
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

   PASS  Tests\Feature\ReservationCrudTest
  ✓ peut afficher la liste des reservations
  ✓ peut afficher le formulaire de creation
  ✓ peut enregistrer une nouvelle reservation
  ✓ la validation echoue si champs manquants
  ✓ peut afficher le detail d une reservation
  ✓ peut afficher le formulaire de modification pre rempli
  ✓ peut modifier une reservation
  ✓ peut supprimer une reservation

  Tests:    10 passed (28 assertions)
```
