<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Utilisateur principal pour les tests (équivalent de Gestionnaire du blogue dans le cours)
        $user = User::factory()->create([
            'name' => 'Gestionnaire des terrain',
            'email' => 'joueur@servicecourt.ca',
            'password' => bcrypt('password'),
        ]);

        // 2. Terrains avec réservations liées grâce à has() et count() (comme Article::factory()->has(Comment::factory()))
        $terrains = Terrain::factory()
            ->count(3)
            ->has(
                Reservation::factory()
                    ->count(2)
                    ->state(fn () => ['user_id' => $user->id])
            )
            ->create();

        // 3. Cas limite : Terrain vide sans réservation (pour tester la gestion de la liste vide dans Blade)
        Terrain::factory()->create([
            'nom_terrain' => 'Court Entraînement (Vide)',
        ]);

        // 4. Cas limite : Réservation à moins de 24h avec un état personnalisé (comme Article::factory()->nonPublie())
        // Utilité démonstration : tester la règle du Récit 3 où l'annulation est interdite à moins de 24h
        Reservation::factory()
            ->moinsDe24Heures()
            ->for($terrains->first())
            ->for($user)
            ->create();

        // 5. Cas limite : Réservation déjà passée
        // Utilité démonstration : tester l'affichage de l'historique et l'impossibilité de modifier dans le passé
        Reservation::factory()
            ->passee()
            ->for($terrains->get(1))
            ->for($user)
            ->create();

        // 6. Cas limite : Réservation annulée
        // Utilité démonstration : vérifier l'affichage distinctif d'une réservation annulée
        Reservation::factory()
            ->annulee()
            ->for($terrains->last())
            ->for($user)
            ->create();
    }
}
