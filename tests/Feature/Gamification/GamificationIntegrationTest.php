<?php

namespace Tests\Feature\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Education\Lesson;
use App\Models\Education\LearningUnit;
use App\Models\Education\LearningProgram;
use App\Models\Education\Assessment;
use App\Models\Education\AssessmentAttempt;
use App\Enums\Education\AttemptStatus;
use App\Services\Education\LearningProgressService;
use App\Services\Gamification\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GamificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_completing_lesson_gives_xp_and_streak()
    {
        $user = User::factory()->create();
        $program = LearningProgram::create(['title' => 'Prog1', 'slug' => 'p1', 'description' => 'desc', 'level' => \App\Enums\Education\ProgramLevel::Beginner, 'status' => \App\Enums\Education\PublishStatus::Published]);
        $unit = LearningUnit::create(['learning_program_id' => $program->id, 'title' => 'U1', 'description' => 'desc', 'order' => 1, 'status' => \App\Enums\Education\PublishStatus::Published]);
        $lesson = Lesson::create(['learning_unit_id' => $unit->id, 'title' => 'L1', 'slug' => 'l1', 'content' => 'c', 'order' => 1, 'status' => \App\Enums\Education\PublishStatus::Published]);

        $service = app(LearningProgressService::class);
        $service->markLessonCompleted($user->id, $lesson->id);

        $this->assertDatabaseHas('gamification_profiles', [
            'user_id' => $user->id,
            'total_xp' => 110,
            'current_streak' => 1,
        ]);
    }
}
