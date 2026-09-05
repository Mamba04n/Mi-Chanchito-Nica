<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UserAchievement extends Model {
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'achievement_id', 'unlocked_at', 'progress', 'metadata'];
    protected $casts = ['unlocked_at' => 'datetime', 'metadata' => 'array'];
    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function achievement() { return $this->belongsTo(Achievement::class); }
}