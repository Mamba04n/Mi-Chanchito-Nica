<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\AccountPayable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayablePaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'account_payable_id' => AccountPayable::factory(),
            'amount' => 100.00,
            'payment_date' => $this->faker->date(),
            'created_by' => User::factory(),
        ];
    }
}
