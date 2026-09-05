<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningProgram extends Model
{
    use HasFactory;

    protected $table = 'learning_programs';

    protected $fillable = ['title', 'slug', 'description', 'level', 'estimated_duration_minutes', 'status', 'featured', 'created_by'];

    protected $casts = ['level' => \App\Enums\Education\ProgramLevel::class, 'status' => \App\Enums\Education\PublishStatus::class, 'featured' => 'boolean'];


    public function creator() { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
    public function sources() { return $this->belongsToMany(EducationalSource::class, 'learning_program_source'); }
    public function units() { return $this->hasMany(LearningUnit::class); }
    public function enrollments() { return $this->hasMany(ProgramEnrollment::class); }
    
    public function competencies() {
        return $this->hasMany(\App\Models\Education\LearningProgramCompetency::class);
    }
        
}
