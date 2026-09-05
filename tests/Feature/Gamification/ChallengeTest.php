<?php

namespace Tests\Feature\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gamification\Challenge;
use App\Models\Gamification\UserChallenge;
use App\Enums\Gamification\ChallengeType;
use App\Enums\Gamification\ChallengeStatus;
use App\Enums\Gamification\XpReason;
use App\Services\Gamification\XpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChallengeTest extends TestCase
{
    use RefreshDatabase;

    public function test_progress_challenge_and_complete()
    {
        $user = User::factory()->create();

        $challenge = Challenge::create([
            'key' => 'earn_200_xp',
            'title' => 'Earn 200 XP',
            'description' => 'Earn 200 XP',
            'challenge_type' => ChallengeType::EarnXp,
            'target_value' => 200,
            'xp_reward' => 100,
            'active' => true,
        ]);

        $uc = UserChallenge::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'status' => ChallengeStatus::Active,
            'progress' => 0,
            'target' => 200,
            'assigned_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);

        $xpService = app(XpService::class);
        $xpService->giveXp($user->id, 150, XpReason::LessonCompleted, 'Lesson', 1);

        $uc->refresh();
        $this->assertEquals(150, $uc->progress);
        $this->assertEquals(ChallengeStatus::Active, $uc->status);

        $xpService->giveXp($user->id, 50, XpReason::LessonCompleted, 'Lesson', 2);

        $uc->refresh();
        $this->assertEquals(200, $uc->progress);
        $this->assertEquals(ChallengeStatus::Completed, $uc->status);

        // Check XP reward added (Total should be 150 + 50 + 100)
        $this->assertDatabaseHas('gamification_profiles', [
            'user_id' => $user->id,
            'total_xp' => 300,
        ]);
    }
}
