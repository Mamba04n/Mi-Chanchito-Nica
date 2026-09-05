<?php

namespace Tests\Feature\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Enums\Gamification\XpReason;
use App\Models\Gamification\GamificationProfile;
use App\Models\Gamification\XpTransaction;
use App\Services\Gamification\XpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class XpTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_give_xp_and_update_profile()
    {
        $user = User::factory()->create();
        $xpService = app(XpService::class);

        $xpService->giveXp($user->id, 50, XpReason::LessonCompleted, 'Lesson', 1);

        $this->assertDatabaseHas('gamification_profiles', [
            'user_id' => $user->id,
            'total_xp' => 50,
            'current_level' => 1,
        ]);

        $this->assertDatabaseHas('xp_transactions', [
            'user_id' => $user->id,
            'amount' => 50,
            'reason' => XpReason::LessonCompleted->value,
            'reference_type' => 'Lesson',
            'reference_id' => 1,
        ]);
    }

    public function test_xp_idempotency()
    {
        $user = User::factory()->create();
        $xpService = app(XpService::class);

        // Give twice for same reason/reference
        $xpService->giveXp($user->id, 50, XpReason::LessonCompleted, 'Lesson', 1);
        $xpService->giveXp($user->id, 50, XpReason::LessonCompleted, 'Lesson', 1);

        $profile = GamificationProfile::where('user_id', $user->id)->first();
        $this->assertEquals(50, $profile->total_xp);
        
        $count = XpTransaction::where('user_id', $user->id)->count();
        $this->assertEquals(1, $count);
    }
}
