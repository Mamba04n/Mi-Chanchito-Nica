<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\User;
use App\Enums\MovementType;

class InventoryMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'warehouse_id' => Warehouse::factory(),
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => MovementType::IN,
            'quantity' => 10,
            'previous_quantity' => 0,
            'new_quantity' => 10,
            'reason' => 'Factory seeded',
            'occurred_at' => now(),
        ];
    }
}
