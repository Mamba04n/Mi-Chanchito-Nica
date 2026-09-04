<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['individual', 'company']),
            'name' => $this->faker->company(),
            'legal_name' => $this->faker->companySuffix(),
            'identification' => $this->faker->numerify('###-######-####U'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'credit_limit' => $this->faker->randomElement([0, 1000, 5000, 10000]),
            'credit_days' => $this->faker->randomElement([0, 15, 30]),
            'notes' => $this->faker->sentence(),
            'active' => true,
        ];
    }
}
