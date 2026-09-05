<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit_cost' => $this->faker->randomFloat(2, 10, 1000),
            'subtotal' => 100.00,
            'total' => 115.00,
        ];
    }
}
