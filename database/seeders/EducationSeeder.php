<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Education\EducationalSource;
use App\Models\Education\LearningProgram;
use App\Models\Education\LearningUnit;
use App\Models\Education\Lesson;
use App\Models\Education\Assessment;
use App\Models\Education\AssessmentQuestion;
use App\Models\Education\AssessmentQuestionOption;
use App\Enums\Education\SourceStatus;
use App\Enums\Education\SourceType;
use App\Enums\Education\PublishStatus;
use App\Enums\Education\ProgramLevel;
use App\Enums\Education\AssessmentType;
use App\Enums\Education\QuestionType;
use Illuminate\Support\Facades\DB;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Source
        $source = EducationalSource::create([
            'institution' => 'Mi Chanchito Nica Academia',
            'title' => 'Contenido demostrativo interno',
            'source_type' => SourceType::Other,
            'url' => 'https://michanchitonica.com',
            'language' => 'es',
            'status' => SourceStatus::Verified,
            'verified_at' => now(),
        ]);

        // 2. Program
        $program = LearningProgram::create([
            'title' => 'Gestión de crédito y cobranza',
            'slug' => 'gestion-credito-y-cobranza',
            'description' => 'Aprende a gestionar tu cartera vencida y políticas de crédito.',
            'level' => ProgramLevel::Intermediate,
            'status' => PublishStatus::Published,
            'featured' => true,
        ]);

        // Associate Competency (pivot or separate table if it exists, but I'll use DB insert for the pivot)
        DB::table('learning_program_competency')->insert([
            'learning_program_id' => $program->id,
            'competency' => 'accounts_receivable_management',
        ]);
        
        DB::table('learning_program_source')->insert([
            'learning_program_id' => $program->id,
            'educational_source_id' => $source->id,
        ]);

        // 3. Unit
        $unit = LearningUnit::create([
            'learning_program_id' => $program->id,
            'title' => 'Control de cartera',
            'description' => 'Conceptos básicos para evitar atrasos.',
            'order' => 1,
            'status' => PublishStatus::Published,
        ]);

        // 4. Lessons
        $lesson1 = Lesson::create([
            'learning_unit_id' => $unit->id,
            'title' => 'Qué es una cuenta por cobrar',
            'slug' => 'que-es-una-cxc',
            'content' => 'Las cuentas por cobrar son...',
            'order' => 1,
            'status' => PublishStatus::Published,
        ]);

        $lesson2 = Lesson::create([
            'learning_unit_id' => $unit->id,
            'title' => 'Vencimientos',
            'slug' => 'vencimientos',
            'content' => 'Entendiendo a 30, 60 y 90 días...',
            'order' => 2,
            'status' => PublishStatus::Published,
        ]);

        // 5. Assessment
        $assessment = Assessment::create([
            'learning_unit_id' => $unit->id,
            'title' => 'Quiz de CxC',
            'description' => 'Prueba de conocimientos',
            'assessment_type' => AssessmentType::Quiz,
            'passing_score' => 70,
            'status' => PublishStatus::Published,
        ]);

        // Questions
        $q1 = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_type' => QuestionType::SingleChoice,
            'question' => '¿Qué es una CxC?',
            'points' => 50,
            'order' => 1,
        ]);
        AssessmentQuestionOption::create(['assessment_question_id' => $q1->id, 'text' => 'Dinero que me deben', 'is_correct' => true, 'order' => 1]);
        AssessmentQuestionOption::create(['assessment_question_id' => $q1->id, 'text' => 'Dinero que debo', 'is_correct' => false, 'order' => 2]);

        $q2 = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_type' => QuestionType::TrueFalse,
            'question' => '¿Un saldo a 90 días es saludable?',
            'points' => 50,
            'order' => 2,
        ]);
        AssessmentQuestionOption::create(['assessment_question_id' => $q2->id, 'text' => 'Verdadero', 'is_correct' => false, 'order' => 1]);
        AssessmentQuestionOption::create(['assessment_question_id' => $q2->id, 'text' => 'Falso', 'is_correct' => true, 'order' => 2]);

        // Progress Demo for first user
        $user = User::first();
        if ($user) {
            app(\App\Services\Education\LearningProgressService::class)->markLessonCompleted($user->id, $lesson1->id);
        }
    }
}
