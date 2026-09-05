<?php
namespace App\Enums\Gamification;
enum ChallengeType: string {
    case CompleteLessons = 'complete_lessons';
    case PassAssessment = 'pass_assessment';
    case EarnXp = 'earn_xp';
    case MaintainStreak = 'maintain_streak';
    case CompleteProgram = 'complete_program';
    case StudyCompetency = 'study_competency';
}