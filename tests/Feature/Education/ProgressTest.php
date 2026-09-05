<?php

namespace Tests\Feature\Education;

use Tests\TestCase;
use App\Models\User;
use App\Models\Education\LearningProgram;
use App\Models\Education\LearningUnit;
use App\Models\Education\Lesson;
use App\Models\Education\LessonProgress;
use App\Models\Education\ProgramEnrollment;
use App\Enums\Education\ProgressStatus;
use App\Enums\Education\EnrollmentStatus;
use App\Services\Education\LearningProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_mark_lesson_completed_and_enroll_program()
    {
        $user = User::factory()->create();
        $program = LearningProgram::create(['title' => 'Prog1', 'slug' => 'p1', 'description' => 'Desc', 'level' => \App\Enums\Education\ProgramLevel::Beginner, 'status' => \App\Enums\Education\PublishStatus::Published]);
        $unit = LearningUnit::create(['learning_program_id' => $program->id, 'title' => 'U1', 'description' => 'desc', 'order' => 1, 'status' => \App\Enums\Education\PublishStatus::Published]);
        $lesson = Lesson::create(['learning_unit_id' => $unit->id, 'title' => 'L1', 'slug' => 'l1', 'content' => 'c', 'order' => 1, 'status' => \App\Enums\Education\PublishStatus::Published]);

        $service = app(LearningProgressService::class);
        $service->markLessonCompleted($user->id, $lesson->id);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'status' => ProgressStatus::Completed,
        ]);

        $this->assertDatabaseHas('program_enrollments', [
            'user_id' => $user->id,
            'learning_program_id' => $program->id,
            'status' => EnrollmentStatus::Completed,
        ]);
    }
}
