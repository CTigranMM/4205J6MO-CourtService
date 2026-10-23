<?php

namespace Database\Factories;

use App\Models\Terrain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Terrain>
 */
class TerrainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_terrain' => 'Court ' . fake()->numberBetween(1, 15),
            'surface' => fake()->randomElement(['Terre battue', 'Gazon synthétique', 'Dur', 'Béton poreux']),
            'emplacement' => fake()->randomElement(['INTERIEUR', 'EXTERIEUR']),
        ];
    }
}
