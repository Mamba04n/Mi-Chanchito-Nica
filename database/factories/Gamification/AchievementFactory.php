<?php

namespace Database\Factories\Gamification;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gamification\Achievement;
use App\Enums\Gamification\AchievementCategory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'icon_key' => 'icon-' . $this->faker->word(),
            'category' => $this->faker->randomElement(AchievementCategory::cases())->value,
            'criteria_type' => $this->faker->word(),
            'criteria_value' => $this->faker->numberBetween(1, 10),
            'xp_reward' => $this->faker->numberBetween(50, 500),
            'active' => true,
        ];
    }
}