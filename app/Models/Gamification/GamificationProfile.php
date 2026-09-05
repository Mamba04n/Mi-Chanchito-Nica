<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GamificationProfile extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'total_xp', 'current_level', 'current_streak', 'longest_streak', 'last_activity_date'];
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}