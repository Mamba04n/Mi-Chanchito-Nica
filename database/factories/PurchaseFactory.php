<?php

namespace Database\Factories;

use App\Enums\PurchaseStatus;
use App\Enums\PurchaseType;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'supplier_id' => Supplier::factory(),
            'number' => 'PO-' . $this->faker->unique()->numberBetween(1000, 9999),
            'supplier_reference' => $this->faker->optional()->word(),
            'purchase_date' => $this->faker->date(),
            'due_date' => $this->faker->optional()->date(),
            'currency' => 'NIO',
            'purchase_type' => $this->faker->randomElement([PurchaseType::CASH->value, PurchaseType::CREDIT->value]),
            'status' => PurchaseStatus::DRAFT->value,
            'subtotal' => 1000.00,
            'tax_total' => 150.00,
            'total' => 1150.00,
            'balance' => 1150.00,
            'created_by' => User::factory(),
        ];
    }
}
