<?php
namespace App\Enums\Gamification;
enum XpReason: string {
    case LessonCompleted = 'lesson_completed';
    case AssessmentPassed = 'assessment_passed';
    case PerfectScore = 'perfect_score';
    case ProgramCompleted = 'program_completed';
    case ChallengeCompleted = 'challenge_completed';
    case AchievementUnlocked = 'achievement_unlocked';
}