<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'message' => $this->faker->sentence,
            'phone' => $this->faker->phoneNumber,
            'status' => 'unread', // par défaut
        ];
    }
}
