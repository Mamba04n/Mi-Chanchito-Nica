<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    use HasFactory;

    protected $table = 'assessment_attempts';

    protected $fillable = ['user_id', 'assessment_id', 'started_at', 'submitted_at', 'score', 'max_score', 'percentage', 'passed', 'attempt_number', 'status'];

    protected $casts = ['status' => \App\Enums\Education\AttemptStatus::class, 'started_at' => 'datetime', 'submitted_at' => 'datetime', 'passed' => 'boolean'];


    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function assessment() { return $this->belongsTo(Assessment::class); }
    public function answers() { return $this->hasMany(AssessmentAnswer::class); }
        
}
