<?php

namespace Database\Factories;

use App\Models\AccountReceivable;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Enums\ReceivableStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountReceivableFactory extends Factory
{
    protected $model = AccountReceivable::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'invoice_id' => Invoice::factory(),
            'original_amount' => $this->faker->randomFloat(2, 10, 1000),
            'paid_amount' => 0,
            'balance' => $this->faker->randomFloat(2, 10, 1000),
            'issued_at' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(ReceivableStatus::cases()),
        ];
    }
}
