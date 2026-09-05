<?php

namespace App\Services\Education;

use App\Models\Education\Lesson;
use App\Models\Education\LessonProgress;
use App\Models\Education\ProgramEnrollment;
use App\Enums\Education\ProgressStatus;
use App\Enums\Education\EnrollmentStatus;
use App\Models\Education\LearningProgram;

class LearningProgressService
{
    public function markLessonCompleted(int $userId, int $lessonId): void
    {
        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['status' => ProgressStatus::InProgress, 'progress_percentage' => 0]
        );

        if ($progress->status !== ProgressStatus::Completed) {
            $progress->status = ProgressStatus::Completed;
            $progress->progress_percentage = 100;
            $progress->completed_at = now();
            $progress->save();

            $lesson = Lesson::with('unit')->find($lessonId);
            if ($lesson) {
                app(\App\Services\Gamification\GamificationService::class)->handleLessonCompleted($userId, $lesson);
                
                if ($lesson->unit) {
                    $this->updateProgramProgress($userId, $lesson->unit->learning_program_id);
                }
            }
        }
    }

    public function updateProgramProgress(int $userId, int $programId): void
    {
        $enrollment = ProgramEnrollment::firstOrCreate(
            ['user_id' => $userId, 'learning_program_id' => $programId],
            ['status' => EnrollmentStatus::Active, 'started_at' => now()]
        );

        $program = LearningProgram::with('units.lessons')->find($programId);
        if (!$program) return;

        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($program->units as $unit) {
            foreach ($unit->lessons as $lesson) {
                $totalLessons++;
                $isCompleted = LessonProgress::where('user_id', $userId)
                    ->where('lesson_id', $lesson->id)
                    ->where('status', ProgressStatus::Completed)
                    ->exists();
                if ($isCompleted) {
                    $completedLessons++;
                }
            }
        }

        if ($totalLessons > 0 && $totalLessons === $completedLessons) {
            if ($enrollment->status !== EnrollmentStatus::Completed) {
                $enrollment->status = EnrollmentStatus::Completed;
                $enrollment->completed_at = now();
                $enrollment->save();
                
                app(\App\Services\Gamification\GamificationService::class)->handleProgramCompleted($userId, $program);
            }
        }
    }
}
