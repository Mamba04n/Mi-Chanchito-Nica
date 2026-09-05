<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\StreakActivity;
use App\Models\User;

class StreakActivityFactory extends Factory
{
    protected $model = StreakActivity::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'activity_date' => now()->toDateString(),
            'source_type' => 'lesson',
            'source_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}