<?php

namespace Database\Factories;

use App\Enums\PayableStatus;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountPayableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'supplier_id' => Supplier::factory(),
            'purchase_id' => Purchase::factory(),
            'original_amount' => 1150.00,
            'balance' => 1150.00,
            'issued_at' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'status' => PayableStatus::PENDING->value,
        ];
    }
}
