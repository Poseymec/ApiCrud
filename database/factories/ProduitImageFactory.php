<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'produit_id' => Produit::factory(),
            'image_path' => $this->faker->image('storage/app/public/produit_images', 640, 480, null, false),
            'is_cover' => $this->faker->boolean(20),
        ];
    }
}
