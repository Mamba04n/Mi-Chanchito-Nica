<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\UserChallenge;
use App\Models\User;
use App\Models\Gamification\Challenge;
use App\Enums\Gamification\ChallengeStatus;

class UserChallengeFactory extends Factory
{
    protected $model = UserChallenge::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'challenge_id' => Challenge::factory(),
            'company_id' => null,
            'status' => $this->faker->randomElement(ChallengeStatus::cases())->value,
            'progress' => 0,
            'target' => 5,
            'assigned_at' => now(),
            'started_at' => null,
            'completed_at' => null,
            'expires_at' => now()->addDays(7),
            'context' => null,
        ];
    }
}