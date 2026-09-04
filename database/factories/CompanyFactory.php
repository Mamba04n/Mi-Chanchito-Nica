<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'country_code' => 'NI',
            'currency_code' => 'NIO',
            'timezone' => 'America/Managua',
            'active' => true,
        ];
    }
}
