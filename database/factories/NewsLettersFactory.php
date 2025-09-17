<?php

namespace Database\Factories;

use App\Models\NewsLetters;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsLettersFactory extends Factory
{
    protected $model = NewsLetters::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
