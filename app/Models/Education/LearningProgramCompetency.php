<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningProgramCompetency extends Model
{
    use HasFactory;

    protected $table = 'learning_program_competency';
    
    public $timestamps = false; // standard pivot tables usually don't have timestamps unless specified

    protected $fillable = ['learning_program_id', 'competency'];

    public function program()
    {
        return $this->belongsTo(LearningProgram::class, 'learning_program_id');
    }
}
