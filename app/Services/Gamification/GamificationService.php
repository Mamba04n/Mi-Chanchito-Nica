<?php
namespace App\Services\Gamification;
use App\Models\Education\Lesson;
use App\Models\Education\AssessmentAttempt;
use App\Models\Education\LearningProgram;
use App\Enums\Gamification\XpReason;
class GamificationService {
    public function __construct(protected XpService $xpService, protected StreakService $streakService, protected AchievementEvaluator $achievementEvaluator, protected ChallengeEvaluator $challengeEvaluator) {}
    public function handleLessonCompleted(int $userId, Lesson $lesson): void {
        $xp = config('gamification.xp.lesson_completed', 10);
        $this->xpService->giveXp($userId, $xp, XpReason::LessonCompleted, Lesson::class, $lesson->id);
        $this->streakService->recordActivity($userId, Lesson::class, $lesson->id);
        $this->achievementEvaluator->evaluate($userId, 'lesson_completed');
        $this->challengeEvaluator->evaluate($userId, 'complete_lessons');
    }
    public function handleAssessmentCompleted(int $userId, AssessmentAttempt $attempt): void {
        if ($attempt->passed) {
            $xp = config('gamification.xp.assessment_passed', 40);
            if ($attempt->percentage == 100) $xp += config('gamification.xp.perfect_score_bonus', 10);
            $this->xpService->giveXp($userId, $xp, XpReason::AssessmentPassed, AssessmentAttempt::class, $attempt->id);
            $this->streakService->recordActivity($userId, AssessmentAttempt::class, $attempt->id);
            $this->achievementEvaluator->evaluate($userId, 'assessment_passed');
            if ($attempt->percentage == 100) $this->achievementEvaluator->evaluate($userId, 'perfect_assessment');
            $this->challengeEvaluator->evaluate($userId, 'pass_assessment');
        }
    }
    public function handleProgramCompleted(int $userId, LearningProgram $program): void {
        $xp = config('gamification.xp.program_completed', 100);
        $this->xpService->giveXp($userId, $xp, XpReason::ProgramCompleted, LearningProgram::class, $program->id);
        $this->streakService->recordActivity($userId, LearningProgram::class, $program->id);
        $this->achievementEvaluator->evaluate($userId, 'program_completed');
        $this->challengeEvaluator->evaluate($userId, 'complete_program');
    }
}