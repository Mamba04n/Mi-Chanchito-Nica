<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'assessment_question_options';

    protected $fillable = ['assessment_question_id', 'text', 'is_correct', 'order'];

    protected $casts = ['is_correct' => 'boolean'];


    public function question() { return $this->belongsTo(AssessmentQuestion::class, 'assessment_question_id'); }
        
}
