<?php

namespace Database\Factories;

use App\Enums\FinancialMovementType;
use App\Models\Company;
use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialMovement>
 */
class FinancialMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'financial_account_id' => FinancialAccount::factory(),
            'type' => fake()->randomElement(FinancialMovementType::cases()),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'currency' => 'NIO',
            'description' => fake()->sentence(),
            'notes' => fake()->optional()->paragraph(),
            'occurred_at' => fake()->dateTimeThisYear(),
            'created_by' => User::factory(),
            'previous_balance' => 0,
            'new_balance' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
