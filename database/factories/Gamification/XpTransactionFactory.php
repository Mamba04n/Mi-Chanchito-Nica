<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\XpTransaction;
use App\Models\User;
use App\Enums\Gamification\XpReason;

class XpTransactionFactory extends Factory
{
    protected $model = XpTransaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->numberBetween(10, 500),
            'reason' => $this->faker->randomElement(XpReason::cases())->value,
            'reference_type' => null,
            'reference_id' => null,
            'metadata' => null,
        ];
    }
}