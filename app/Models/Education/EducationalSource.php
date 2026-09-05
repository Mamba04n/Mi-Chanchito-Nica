<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalSource extends Model
{
    use HasFactory;

    protected $table = 'educational_sources';

    protected $fillable = ['institution', 'title', 'author', 'source_type', 'url', 'language', 'published_at', 'license', 'license_url', 'verified_at', 'status'];

    protected $casts = ['source_type' => \App\Enums\Education\SourceType::class, 'status' => \App\Enums\Education\SourceStatus::class, 'published_at' => 'date', 'verified_at' => 'datetime'];


    public function documents() { return $this->hasMany(EducationalDocument::class); }
    public function learningPrograms() { return $this->belongsToMany(LearningProgram::class, 'learning_program_source'); }
        
}
