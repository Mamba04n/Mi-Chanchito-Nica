<?php

namespace Tests\Feature\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gamification\GamificationProfile;
use App\Services\Gamification\StreakService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class StreakTest extends TestCase
{
    use RefreshDatabase;

    public function test_streak_starts_at_one_and_increments_next_day()
    {
        $user = User::factory()->create();
        $streakService = app(StreakService::class);

        // Mock today
        $today = Carbon::create(2026, 9, 1, 12, 0, 0);
        Carbon::setTestNow($today);

        $streakService->recordActivity($user->id, 'Lesson', 1);

        $profile = GamificationProfile::where('user_id', $user->id)->first();
        $this->assertEquals(1, $profile->current_streak);
        $this->assertEquals(1, $profile->longest_streak);

        // Same day activity doesn't increment
        $streakService->recordActivity($user->id, 'Lesson', 2);
        $profile->refresh();
        $this->assertEquals(1, $profile->current_streak);

        // Next day activity increments
        $tomorrow = Carbon::create(2026, 9, 2, 12, 0, 0);
        Carbon::setTestNow($tomorrow);
        
        $streakService->recordActivity($user->id, 'Lesson', 3);
        $profile->refresh();
        $this->assertEquals(2, $profile->current_streak);
        $this->assertEquals(2, $profile->longest_streak);

        // Skip a day resets streak
        $future = Carbon::create(2026, 9, 4, 12, 0, 0);
        Carbon::setTestNow($future);

        $streakService->recordActivity($user->id, 'Lesson', 4);
        $profile->refresh();
        $this->assertEquals(1, $profile->current_streak);
        $this->assertEquals(2, $profile->longest_streak); // Longest remains 2

        Carbon::setTestNow(); // reset
    }
}
