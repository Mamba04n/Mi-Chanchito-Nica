<?php

namespace App\Services\Education;

use App\Models\Education\AssessmentAttempt;
use App\Models\Education\AssessmentQuestionOption;
use App\Enums\Education\AttemptStatus;
use Illuminate\Support\Facades\DB;

class GradeAssessment
{
    public function grade(AssessmentAttempt $attempt): AssessmentAttempt
    {
        if ($attempt->status === AttemptStatus::Graded) {
            return $attempt; // already graded
        }

        return DB::transaction(function () use ($attempt) {
            $totalPoints = 0;

            // Load the assessment and questions
            $attempt->load('assessment.questions');
            $questions = $attempt->assessment->questions->keyBy('id');

            // Load answers
            $attempt->load('answers');

            foreach ($attempt->answers as $answer) {
                $question = $questions->get($answer->assessment_question_id);
                if (!$question) continue;

                if ($answer->assessment_question_option_id) {
                    $option = AssessmentQuestionOption::find($answer->assessment_question_option_id);
                    if ($option && $option->is_correct) {
                        $answer->is_correct = true;
                        $answer->points_awarded = $question->points;
                        $totalPoints += $question->points;
                    } else {
                        $answer->is_correct = false;
                        $answer->points_awarded = 0;
                    }
                } else {
                    $answer->is_correct = false;
                    $answer->points_awarded = 0;
                }
                
                $answer->save();
            }

            $actualMaxPoints = $attempt->assessment->questions->sum('points');
            $percentage = $actualMaxPoints > 0 ? ($totalPoints / $actualMaxPoints) * 100 : 0;
            
            $attempt->score = $totalPoints;
            $attempt->max_score = $actualMaxPoints;
            $attempt->percentage = $percentage;
            $attempt->passed = $percentage >= $attempt->assessment->passing_score;
            $attempt->status = AttemptStatus::Graded;
            $attempt->save();

            return $attempt;
        });
    }
}
