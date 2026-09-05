<?php

namespace Tests\Feature\Education;

use Tests\TestCase;
use App\Models\User;
use App\Models\Education\Assessment;
use App\Models\Education\AssessmentQuestion;
use App\Models\Education\AssessmentQuestionOption;
use App\Models\Education\AssessmentAttempt;
use App\Models\Education\AssessmentAnswer;
use App\Enums\Education\AssessmentType;
use App\Enums\Education\QuestionType;
use App\Enums\Education\AttemptStatus;
use App\Services\Education\GradeAssessment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssessmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_grade_assessment_passed()
    {
        $user = User::factory()->create();
        
        $assessment = Assessment::create([
            'title' => 'Test Exam',
            'assessment_type' => AssessmentType::Exam,
            'passing_score' => 70,
            'status' => \App\Enums\Education\PublishStatus::Published,
        ]);

        $q1 = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_type' => QuestionType::SingleChoice,
            'question' => 'Q1',
            'points' => 50,
            'order' => 1,
        ]);
        $q1Correct = AssessmentQuestionOption::create(['assessment_question_id' => $q1->id, 'text' => 'Correct', 'is_correct' => true, 'order' => 1]);
        $q1Wrong = AssessmentQuestionOption::create(['assessment_question_id' => $q1->id, 'text' => 'Wrong', 'is_correct' => false, 'order' => 2]);

        $q2 = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_type' => QuestionType::TrueFalse,
            'question' => 'Q2',
            'points' => 50,
            'order' => 2,
        ]);
        $q2Correct = AssessmentQuestionOption::create(['assessment_question_id' => $q2->id, 'text' => 'True', 'is_correct' => true, 'order' => 1]);

        $attempt = AssessmentAttempt::create([
            'user_id' => $user->id,
            'assessment_id' => $assessment->id,
            'started_at' => now(),
            'attempt_number' => 1,
            'status' => AttemptStatus::Submitted,
            'max_score' => 100, // Service recalculates it
        ]);

        AssessmentAnswer::create([
            'assessment_attempt_id' => $attempt->id,
            'assessment_question_id' => $q1->id,
            'assessment_question_option_id' => $q1Correct->id,
        ]);

        AssessmentAnswer::create([
            'assessment_attempt_id' => $attempt->id,
            'assessment_question_id' => $q2->id,
            'assessment_question_option_id' => $q2Correct->id,
        ]);

        $service = app(GradeAssessment::class);
        $service->grade($attempt);

        $attempt->refresh();
        $this->assertEquals(100, $attempt->score);
        $this->assertEquals(100, $attempt->percentage);
        $this->assertTrue($attempt->passed);
        $this->assertEquals(AttemptStatus::Graded, $attempt->status);
    }

    public function test_can_grade_assessment_failed()
    {
        $user = User::factory()->create();
        
        $assessment = Assessment::create([
            'title' => 'Test Exam 2',
            'assessment_type' => AssessmentType::Exam,
            'passing_score' => 70,
            'status' => \App\Enums\Education\PublishStatus::Published,
        ]);

        $q1 = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_type' => QuestionType::SingleChoice,
            'question' => 'Q1',
            'points' => 50,
            'order' => 1,
        ]);
        $q1Correct = AssessmentQuestionOption::create(['assessment_question_id' => $q1->id, 'text' => 'Correct', 'is_correct' => true, 'order' => 1]);
        $q1Wrong = AssessmentQuestionOption::create(['assessment_question_id' => $q1->id, 'text' => 'Wrong', 'is_correct' => false, 'order' => 2]);

        $q2 = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_type' => QuestionType::TrueFalse,
            'question' => 'Q2',
            'points' => 50,
            'order' => 2,
        ]);
        $q2Correct = AssessmentQuestionOption::create(['assessment_question_id' => $q2->id, 'text' => 'True', 'is_correct' => true, 'order' => 1]);

        $attempt = AssessmentAttempt::create([
            'user_id' => $user->id,
            'assessment_id' => $assessment->id,
            'started_at' => now(),
            'attempt_number' => 1,
            'status' => AttemptStatus::Submitted,
            'max_score' => 100,
        ]);

        // Only answer q1 correctly (50/100) -> 50%
        AssessmentAnswer::create([
            'assessment_attempt_id' => $attempt->id,
            'assessment_question_id' => $q1->id,
            'assessment_question_option_id' => $q1Correct->id,
        ]);

        $service = app(GradeAssessment::class);
        $service->grade($attempt);

        $attempt->refresh();
        $this->assertEquals(50, $attempt->score);
        $this->assertEquals(100, $attempt->max_score);
        $this->assertEquals(50, $attempt->percentage);
        $this->assertFalse($attempt->passed);
    }
}
