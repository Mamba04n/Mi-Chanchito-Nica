<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningUnit extends Model
{
    use HasFactory;

    protected $table = 'learning_units';

    protected $fillable = ['learning_program_id', 'title', 'description', 'order', 'estimated_duration_minutes', 'status'];

    protected $casts = ['status' => \App\Enums\Education\PublishStatus::class];


    public function program() { return $this->belongsTo(LearningProgram::class, 'learning_program_id'); }
    public function lessons() { return $this->hasMany(Lesson::class); }
        
}
