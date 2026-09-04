<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\Warehouse;
use App\Models\Product;

class InventoryStockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'warehouse_id' => Warehouse::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->randomFloat(2, 0, 1000),
            'reserved_quantity' => 0,
            'minimum_stock' => $this->faker->randomFloat(2, 5, 50),
            'maximum_stock' => $this->faker->randomFloat(2, 100, 2000),
        ];
    }
}
