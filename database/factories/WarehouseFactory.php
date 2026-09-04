<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

class WarehouseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'code' => $this->faker->unique()->bothify('WH-####'),
            'name' => 'Bodega ' . $this->faker->city(),
            'description' => $this->faker->sentence(),
            'address' => $this->faker->address(),
            'active' => true,
            'is_default' => false,
        ];
    }
}
