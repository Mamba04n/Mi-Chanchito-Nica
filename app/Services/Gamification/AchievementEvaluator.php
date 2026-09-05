<?php
namespace App\Services\Gamification;
use App\Models\Gamification\Achievement;
use App\Models\Gamification\UserAchievement;
use App\Models\Education\LessonProgress;
use App\Models\Education\AssessmentAttempt;
use App\Enums\Gamification\XpReason;
use Illuminate\Support\Facades\DB;
class AchievementEvaluator {
    public function evaluate(int $userId, string $event, $context = null): void {
        $achievements = Achievement::where('active', true)->where('criteria_type', $event)->get();
        foreach ($achievements as $achievement) {
            if (UserAchievement::where('user_id', $userId)->where('achievement_id', $achievement->id)->exists()) continue;
            if ($this->checkCriteria($userId, $achievement, $context)) {
                DB::transaction(function () use ($userId, $achievement) {
                    UserAchievement::create(['user_id' => $userId, 'achievement_id' => $achievement->id, 'unlocked_at' => now(), 'progress' => $achievement->criteria_value]);
                    if ($achievement->xp_reward > 0) { app(XpService::class)->giveXp($userId, $achievement->xp_reward, XpReason::AchievementUnlocked, Achievement::class, $achievement->id); }
                });
            }
        }
    }
    protected function checkCriteria(int $userId, Achievement $achievement, $context): bool {
        $criteria = $achievement->criteria_type; $target = $achievement->criteria_value;
        if ($criteria === 'earn_xp') return $context >= $target;
        if ($criteria === 'lesson_completed') return LessonProgress::where('user_id', $userId)->where('status', \App\Enums\Education\ProgressStatus::Completed)->count() >= $target;
        if ($criteria === 'streak_updated') return $context >= $target;
        if ($criteria === 'assessment_passed') return AssessmentAttempt::where('user_id', $userId)->where('passed', true)->count() >= $target;
        if ($criteria === 'perfect_assessment') return AssessmentAttempt::where('user_id', $userId)->where('passed', true)->where('percentage', 100)->count() >= $target;
        return false;
    }
}