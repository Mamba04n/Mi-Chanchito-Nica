<?php
namespace App\Services\Gamification;
class LevelService {
    public function getLevels(): array {
        return config('gamification.levels');
    }
    public function getLevelForXp(int $xp): int {
        $levels = $this->getLevels();
        $currentLevel = 1;
        foreach ($levels as $level => $requiredXp) {
            if ($xp >= $requiredXp) $currentLevel = $level;
            else break;
        }
        return $currentLevel;
    }
    public function getLevelProgress(int $xp): array {
        $levels = $this->getLevels();
        $currentLevel = $this->getLevelForXp($xp);
        $currentLevelXp = $levels[$currentLevel] ?? 0;
        $nextLevel = $currentLevel + 1;
        $nextLevelXp = $levels[$nextLevel] ?? null;
        if ($nextLevelXp === null) return ['current_level' => $currentLevel, 'current_xp' => $xp, 'next_level' => null, 'xp_required_for_next' => 0, 'progress_percentage' => 100];
        $xpInCurrentLevel = $xp - $currentLevelXp;
        $xpNeededForNext = $nextLevelXp - $currentLevelXp;
        $progress = ($xpNeededForNext > 0) ? min(100, round(($xpInCurrentLevel / $xpNeededForNext) * 100)) : 100;
        return ['current_level' => $currentLevel, 'current_xp' => $xp, 'next_level' => $nextLevel, 'xp_required_for_next' => $nextLevelXp - $xp, 'progress_percentage' => $progress];
    }
}