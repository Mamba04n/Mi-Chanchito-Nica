<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UserChallenge extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'challenge_id', 'company_id', 'status', 'progress', 'target', 'assigned_at', 'started_at', 'completed_at', 'expires_at', 'context'];
    protected $casts = ['status' => \App\Enums\Gamification\ChallengeStatus::class, 'assigned_at' => 'datetime', 'started_at' => 'datetime', 'completed_at' => 'datetime', 'expires_at' => 'datetime', 'context' => 'array'];
    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function challenge() { return $this->belongsTo(Challenge::class); }
    public function company() { return $this->belongsTo(\App\Models\Company::class); }
}