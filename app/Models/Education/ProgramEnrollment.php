<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramEnrollment extends Model
{
    use HasFactory;

    protected $table = 'program_enrollments';

    protected $fillable = ['user_id', 'learning_program_id', 'status', 'started_at', 'completed_at'];

    protected $casts = ['status' => \App\Enums\Education\EnrollmentStatus::class, 'started_at' => 'datetime', 'completed_at' => 'datetime'];


    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function program() { return $this->belongsTo(LearningProgram::class, 'learning_program_id'); }
        
}
