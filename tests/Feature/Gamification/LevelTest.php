<?php

namespace Tests\Feature\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Enums\Gamification\XpReason;
use App\Models\Gamification\GamificationProfile;
use App\Services\Gamification\XpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_up_when_xp_threshold_reached()
    {
        $user = User::factory()->create();
        $xpService = app(XpService::class);

        // level 2 requires 100 XP
        $xpService->giveXp($user->id, 50, XpReason::LessonCompleted, 'Lesson', 1);
        
        $profile = GamificationProfile::where('user_id', $user->id)->first();
        $this->assertEquals(1, $profile->current_level);

        $xpService->giveXp($user->id, 60, XpReason::LessonCompleted, 'Lesson', 2);
        
        $profile->refresh();
        $this->assertEquals(110, $profile->total_xp);
        $this->assertEquals(2, $profile->current_level);
    }
}
