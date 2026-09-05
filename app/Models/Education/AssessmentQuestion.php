<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $table = 'assessment_questions';

    protected $fillable = ['assessment_id', 'question_type', 'question', 'explanation', 'points', 'order', 'metadata'];

    protected $casts = ['question_type' => \App\Enums\Education\QuestionType::class, 'metadata' => 'array'];


    public function assessment() { return $this->belongsTo(Assessment::class); }
    public function options() { return $this->hasMany(AssessmentQuestionOption::class); }
        
}
