<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $table = 'lesson_progress';

    protected $fillable = ['user_id', 'lesson_id', 'status', 'progress_percentage', 'started_at', 'completed_at', 'last_activity_at'];

    protected $casts = ['status' => \App\Enums\Education\ProgressStatus::class, 'started_at' => 'datetime', 'completed_at' => 'datetime', 'last_activity_at' => 'datetime'];


    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function lesson() { return $this->belongsTo(Lesson::class); }
        
}
