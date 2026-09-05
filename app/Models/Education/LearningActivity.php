<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningActivity extends Model
{
    use HasFactory;

    protected $table = 'learning_activities';

    protected $fillable = ['lesson_id', 'type', 'title', 'instructions', 'order', 'required', 'metadata'];

    protected $casts = ['required' => 'boolean', 'metadata' => 'array'];


    public function lesson() { return $this->belongsTo(Lesson::class); }
        
}
