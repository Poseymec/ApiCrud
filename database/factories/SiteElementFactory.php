<?php

namespace Database\Factories;

use App\Models\SiteElementCategorie;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteElementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'site_element_categorie_id' => SiteElementCategorie::factory(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['text', 'file']),
            'content' => $this->faker->text(200), // ✅ Limiter à 200 caractères
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
