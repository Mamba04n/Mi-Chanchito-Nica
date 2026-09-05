<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    use HasFactory;

    protected $table = 'assessment_answers';

    protected $fillable = ['assessment_attempt_id', 'assessment_question_id', 'assessment_question_option_id', 'answer_data', 'points_awarded', 'is_correct'];

    protected $casts = ['is_correct' => 'boolean'];


    public function attempt() { return $this->belongsTo(AssessmentAttempt::class, 'assessment_attempt_id'); }
    public function question() { return $this->belongsTo(AssessmentQuestion::class, 'assessment_question_id'); }
    public function option() { return $this->belongsTo(AssessmentQuestionOption::class, 'assessment_question_option_id'); }
        
}
