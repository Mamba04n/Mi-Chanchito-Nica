<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';

    protected $fillable = ['lesson_id', 'learning_unit_id', 'title', 'description', 'assessment_type', 'passing_score', 'max_attempts', 'time_limit_minutes', 'status'];

    protected $casts = ['assessment_type' => \App\Enums\Education\AssessmentType::class, 'status' => \App\Enums\Education\PublishStatus::class];


    public function lesson() { return $this->belongsTo(Lesson::class); }
    public function unit() { return $this->belongsTo(LearningUnit::class, 'learning_unit_id'); }
    public function questions() { return $this->hasMany(AssessmentQuestion::class); }
    public function attempts() { return $this->hasMany(AssessmentAttempt::class); }
        
}
