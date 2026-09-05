<?php
namespace App\Services\Gamification;
use App\Models\Gamification\GamificationProfile;
use App\Models\Gamification\UserAchievement;
use App\Models\Gamification\UserChallenge;
use App\Models\Gamification\XpTransaction;
use App\Enums\Gamification\ChallengeStatus;
class GamificationDashboardService {
    public function __construct(protected LevelService $levelService) {}
    public function getDashboardData(int $userId): array {
        $profile = GamificationProfile::firstOrCreate(['user_id' => $userId], ['total_xp' => 0, 'current_level' => 1, 'current_streak' => 0, 'longest_streak' => 0]);
        $levelProgress = $this->levelService->getLevelProgress($profile->total_xp);
        $recentAchievements = UserAchievement::where('user_id', $userId)->with('achievement')->orderBy('unlocked_at', 'desc')->take(5)->get();
        $activeChallenges = UserChallenge::where('user_id', $userId)->where('status', ChallengeStatus::Active)->with('challenge')->orderBy('expires_at', 'asc')->get();
        $recentActivity = XpTransaction::where('user_id', $userId)->orderBy('created_at', 'desc')->take(10)->get();
        return ['profile' => ['total_xp' => $profile->total_xp, 'current_level' => $profile->current_level, 'current_streak' => $profile->current_streak, 'longest_streak' => $profile->longest_streak], 'level_progress' => $levelProgress, 'recent_achievements' => $recentAchievements, 'active_challenges' => $activeChallenges, 'recent_xp_activity' => $recentActivity];
    }
}