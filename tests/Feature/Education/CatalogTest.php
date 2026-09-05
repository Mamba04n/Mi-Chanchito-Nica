<?php

namespace Tests\Feature\Education;

use Tests\TestCase;
use App\Models\Education\LearningProgram;
use App\Enums\Education\PublishStatus;
use App\Services\Education\LearningCatalogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_find_published_programs()
    {
        LearningProgram::create(['title' => 'P1', 'slug' => 'p1', 'description' => 'desc', 'level' => \App\Enums\Education\ProgramLevel::Beginner, 'status' => PublishStatus::Published]);
        LearningProgram::create(['title' => 'P2', 'slug' => 'p2', 'description' => 'desc', 'level' => \App\Enums\Education\ProgramLevel::Beginner, 'status' => PublishStatus::Draft]);

        $service = app(LearningCatalogService::class);
        $programs = $service->getPublishedPrograms();

        $this->assertCount(1, $programs);
        $this->assertEquals('P1', $programs->first()->title);
    }

    public function test_can_find_programs_by_competency()
    {
        $p1 = LearningProgram::create(['title' => 'P1', 'slug' => 'p3', 'description' => 'desc', 'level' => \App\Enums\Education\ProgramLevel::Beginner, 'status' => PublishStatus::Published]);
        DB::table('learning_program_competency')->insert([
            'learning_program_id' => $p1->id,
            'competency' => 'inventory_management'
        ]);

        $p2 = LearningProgram::create(['title' => 'P2', 'slug' => 'p4', 'description' => 'desc', 'level' => \App\Enums\Education\ProgramLevel::Beginner, 'status' => PublishStatus::Published]);
        DB::table('learning_program_competency')->insert([
            'learning_program_id' => $p2->id,
            'competency' => 'cash_flow_management'
        ]);

        $service = app(LearningCatalogService::class);
        $programs = $service->findProgramsByCompetency('inventory_management');

        $this->assertCount(1, $programs);
        $this->assertEquals('P1', $programs->first()->title);
    }
}
