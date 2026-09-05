<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\Challenge;
use App\Enums\Gamification\ChallengeType;

class ChallengeFactory extends Factory
{
    protected $model = Challenge::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(),
            'title' => $this->faker->words(4, true),
            'description' => $this->faker->sentence(),
            'challenge_type' => $this->faker->randomElement(ChallengeType::cases())->value,
            'competency' => $this->faker->word(),
            'target_value' => $this->faker->numberBetween(1, 10),
            'xp_reward' => $this->faker->numberBetween(100, 1000),
            'start_at' => now(),
            'end_at' => now()->addDays(7),
            'active' => true,
        ];
    }
}