<?php

namespace Database\Factories;

use App\Enums\FinancialAccountType;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialAccount>
 */
class FinancialAccountFactory extends Factory
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
            'name' => fake()->company() . ' Account',
            'type' => fake()->randomElement(FinancialAccountType::cases()),
            'currency' => 'NIO',
            'opening_balance' => 0,
            'current_balance' => 0,
            'description' => fake()->sentence(),
            'active' => true,
            'is_default' => false,
        ];
    }
}
