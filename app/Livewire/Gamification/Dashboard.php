<?php

namespace App\Livewire\Gamification;

use Livewire\Component;
use App\Models\Gamification\GamificationProfile;
use App\Models\Gamification\UserAchievement;
use App\Models\Gamification\UserChallenge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $profile = GamificationProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['total_xp' => 0, 'current_level' => 1, 'current_streak' => 0]
        );
        
        $achievements = UserAchievement::with('achievement')
            ->where('user_id', $user->id)
            ->get();
            
        $challenges = UserChallenge::with('challenge')
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->get();
            
        $leaderboard = GamificationProfile::with('user')
            ->orderBy('total_xp', 'desc')
            ->take(10)
            ->get();

        return view('livewire.gamification.dashboard', [
            'profile' => $profile,
            'achievements' => $achievements,
            'challenges' => $challenges,
            'leaderboard' => $leaderboard
        ])->layout('layouts.app');
    }
}
