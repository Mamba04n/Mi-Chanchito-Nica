<?php

namespace App\Livewire\Education;

use Livewire\Component;
use App\Models\Education\ProgramEnrollment;
use App\Models\Education\LearningProgram;
use App\Models\Gamification\GamificationProfile;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Progress global del usuario
        $progress = GamificationProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['total_xp' => 0, 'current_level' => 1, 'current_streak' => 0]
        );

        // Enrolled programs (En progreso o completados)
        $enrollments = ProgramEnrollment::with('program')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();
            
        // Featured programs (Recomendados que no tenga)
        $enrolledIds = $enrollments->pluck('learning_program_id')->toArray();
        $featuredPrograms = LearningProgram::where('featured', true)
            ->where('status', 'published')
            ->whereNotIn('id', $enrolledIds)
            ->take(4)
            ->get();

        return view('livewire.education.home', [
            'enrollments' => $enrollments,
            'featuredPrograms' => $featuredPrograms,
            'progress' => $progress
        ])->layout('layouts.app');
    }
}
