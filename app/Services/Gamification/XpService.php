<?php
namespace App\Services\Gamification;
use App\Models\Gamification\GamificationProfile;
use App\Models\Gamification\XpTransaction;
use Illuminate\Support\Facades\DB;
class XpService {
    public function __construct(protected LevelService $levelService, protected AchievementEvaluator $achievementEvaluator, protected ChallengeEvaluator $challengeEvaluator) {}
    public function giveXp(int $userId, int $amount, \App\Enums\Gamification\XpReason $reason, ?string $referenceType = null, ?int $referenceId = null, ?array $metadata = null): void {
        if ($amount <= 0) return;
        DB::transaction(function () use ($userId, $amount, $reason, $referenceType, $referenceId, $metadata) {
            if ($referenceType && $referenceId) {
                if (XpTransaction::where('user_id', $userId)->where('reason', $reason)->where('reference_type', $referenceType)->where('reference_id', $referenceId)->exists()) return;
            }
            $profile = GamificationProfile::where('user_id', $userId)->lockForUpdate()->firstOrCreate(['user_id' => $userId], ['total_xp' => 0, 'current_level' => 1]);
            XpTransaction::create(['user_id' => $userId, 'amount' => $amount, 'reason' => $reason, 'reference_type' => $referenceType, 'reference_id' => $referenceId, 'metadata' => $metadata]);
            $profile->total_xp += $amount;
            $profile->current_level = $this->levelService->getLevelForXp($profile->total_xp);
            $profile->save();
            $this->achievementEvaluator->evaluate($userId, 'earn_xp', $profile->total_xp);
            $this->challengeEvaluator->evaluate($userId, 'earn_xp', $amount);
        });
    }
}