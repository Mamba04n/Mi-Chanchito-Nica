<?php

namespace Tests\Feature\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gamification\Achievement;
use App\Models\Gamification\UserAchievement;
use App\Enums\Gamification\AchievementCategory;
use App\Enums\Gamification\XpReason;
use App\Services\Gamification\XpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unlocks_achievement_and_gives_xp()
    {
        $user = User::factory()->create();
        
        $achievement = Achievement::create([
            'key' => '100_xp_club',
            'name' => '100 XP Club',
            'description' => 'Reach 100 XP',
            'category' => AchievementCategory::Activity,
            'criteria_type' => 'earn_xp',
            'criteria_value' => 100,
            'xp_reward' => 50,
        ]);

        $xpService = app(XpService::class);
        $xpService->giveXp($user->id, 100, XpReason::LessonCompleted, 'Lesson', 1);

        // Check achievement unlocked
        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
        ]);

        // Check XP reward added (Total should be 100 + 50)
        $this->assertDatabaseHas('gamification_profiles', [
            'user_id' => $user->id,
            'total_xp' => 150,
        ]);
    }
}
