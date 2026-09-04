<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $isProduct = $this->faker->boolean(80);
        return [
            'sku' => $this->faker->unique()->bothify('PROD-####'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $isProduct ? 'product' : 'service',
            'sale_price' => $this->faker->randomFloat(2, 10, 1000),
            'cost' => $this->faker->randomFloat(2, 5, 500),
            'track_inventory' => $isProduct,
            'active' => true,
        ];
    }
}
