<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\GamificationProfile;
use App\Models\User;

class GamificationProfileFactory extends Factory
{
    protected $model = GamificationProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_xp' => $this->faker->numberBetween(0, 10000),
            'current_level' => $this->faker->numberBetween(1, 50),
            'current_streak' => $this->faker->numberBetween(0, 30),
            'longest_streak' => $this->faker->numberBetween(0, 100),
            'last_activity_date' => $this->faker->date(),
        ];
    }
}