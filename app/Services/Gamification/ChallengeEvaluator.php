<?php
namespace App\Services\Gamification;
use App\Models\Gamification\UserChallenge;
use App\Models\Gamification\Challenge;
use App\Enums\Gamification\ChallengeStatus;
use App\Enums\Gamification\XpReason;
use Illuminate\Support\Facades\DB;
class ChallengeEvaluator {
    public function evaluate(int $userId, string $event, $context = null): void {
        $userChallenges = UserChallenge::where('user_id', $userId)->where('status', ChallengeStatus::Active)->whereHas('challenge', function ($q) use ($event) { $q->where('challenge_type', $event); })->with('challenge')->get();
        foreach ($userChallenges as $uc) {
            if ($uc->expires_at && $uc->expires_at < now()) { $uc->status = ChallengeStatus::Expired; $uc->save(); continue; }
            $progressDelta = ($event === 'earn_xp') ? (int) $context : 1;
            DB::transaction(function () use ($userId, $uc, $progressDelta) {
                $lockedUc = UserChallenge::where('id', $uc->id)->lockForUpdate()->first();
                if ($lockedUc->status !== ChallengeStatus::Active) return;
                $lockedUc->progress += $progressDelta;
                if ($lockedUc->progress >= $lockedUc->target) {
                    $lockedUc->progress = $lockedUc->target; $lockedUc->status = ChallengeStatus::Completed; $lockedUc->completed_at = now(); $lockedUc->save();
                    if ($lockedUc->challenge->xp_reward > 0) { app(XpService::class)->giveXp($userId, $lockedUc->challenge->xp_reward, XpReason::ChallengeCompleted, Challenge::class, $lockedUc->challenge->id); }
                } else { $lockedUc->save(); }
            });
        }
    }
}