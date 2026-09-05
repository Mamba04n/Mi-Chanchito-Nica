<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\UserAchievement;
use App\Models\User;
use App\Models\Gamification\Achievement;

class UserAchievementFactory extends Factory
{
    protected $model = UserAchievement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'achievement_id' => Achievement::factory(),
            'unlocked_at' => now(),
            'progress' => 100,
            'metadata' => null,
        ];
    }
}