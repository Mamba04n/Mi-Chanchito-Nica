<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = ['learning_unit_id', 'title', 'slug', 'summary', 'content', 'content_type', 'order', 'estimated_duration_minutes', 'status'];

    protected $casts = ['status' => \App\Enums\Education\PublishStatus::class];


    public function unit() { return $this->belongsTo(LearningUnit::class, 'learning_unit_id'); }
    public function activities() { return $this->hasMany(LearningActivity::class); }
    public function assessments() { return $this->hasMany(Assessment::class); }
        
}
