<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => 'company',
            'name' => $this->faker->company(),
            'legal_name' => $this->faker->company(),
            'identification' => $this->faker->numerify('J#########'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'payment_terms_days' => $this->faker->randomElement([0, 15, 30, 60]),
            'notes' => $this->faker->sentence(),
            'active' => true,
        ];
    }
}
