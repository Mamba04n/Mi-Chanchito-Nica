<?php

namespace Database\Factories;

use App\Models\ReceivablePayment;
use App\Models\Company;
use App\Models\AccountReceivable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceivablePaymentFactory extends Factory
{
    protected $model = ReceivablePayment::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'account_receivable_id' => AccountReceivable::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'payment_date' => $this->faker->date(),
            'reference' => $this->faker->bothify('REF-####'),
            'notes' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
