<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Challenge extends Model {
    use HasFactory;
    protected $fillable = ['key', 'title', 'description', 'challenge_type', 'competency', 'target_value', 'xp_reward', 'start_at', 'end_at', 'active'];
    protected $casts = ['challenge_type' => \App\Enums\Gamification\ChallengeType::class, 'start_at' => 'datetime', 'end_at' => 'datetime', 'active' => 'boolean'];
}