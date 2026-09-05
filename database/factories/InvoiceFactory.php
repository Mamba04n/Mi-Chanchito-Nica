<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'number' => $this->faker->unique()->bothify('INV-####-????'),
            'issue_date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'currency' => 'NIO',
            'sale_type' => $this->faker->randomElement(SaleType::cases()),
            'status' => $this->faker->randomElement(InvoiceStatus::cases()),
            'subtotal' => $this->faker->randomFloat(2, 10, 1000),
            'discount_total' => 0,
            'tax_total' => 0,
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'paid_amount' => 0,
            'balance' => $this->faker->randomFloat(2, 10, 1000),
            'notes' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
