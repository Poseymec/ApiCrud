<?php

namespace Database\Factories;
  use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categorie_id' => Categorie::factory(),
            'name' => $this->faker->words(2, true),
            'description1' => $this->faker->sentence(),
            'description2' => $this->faker->paragraph(),
            'prix' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
