<?php
namespace App\Services\Gamification;
use App\Models\Gamification\GamificationProfile;
use App\Models\Gamification\StreakActivity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class StreakService {
    public function __construct(protected AchievementEvaluator $achievementEvaluator, protected ChallengeEvaluator $challengeEvaluator) {}
    public function recordActivity(int $userId, string $sourceType, int $sourceId): void {
        $today = Carbon::today(config('app.timezone'));
        DB::transaction(function () use ($userId, $sourceType, $sourceId, $today) {
            if (StreakActivity::where('user_id', $userId)->whereDate('activity_date', $today)->exists()) return;
            StreakActivity::create(['user_id' => $userId, 'activity_date' => $today, 'source_type' => $sourceType, 'source_id' => $sourceId]);
            $profile = GamificationProfile::where('user_id', $userId)->lockForUpdate()->firstOrCreate(['user_id' => $userId], ['total_xp' => 0, 'current_level' => 1, 'current_streak' => 0, 'longest_streak' => 0]);
            $lastActivity = $profile->last_activity_date ? Carbon::parse($profile->last_activity_date) : null;
            if ($lastActivity && $lastActivity->isSameDay($today->copy()->subDay())) $profile->current_streak += 1;
            elseif ($lastActivity && $lastActivity->isSameDay($today)) {}
            else $profile->current_streak = 1;
            if ($profile->current_streak > $profile->longest_streak) $profile->longest_streak = $profile->current_streak;
            $profile->last_activity_date = $today; $profile->save();
            $this->achievementEvaluator->evaluate($userId, 'streak_updated', $profile->current_streak);
            $this->challengeEvaluator->evaluate($userId, 'maintain_streak', $profile->current_streak);
        });
    }
}