<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Achievement extends Model {
    use HasFactory;
    protected $fillable = ['key', 'name', 'description', 'icon_key', 'category', 'criteria_type', 'criteria_value', 'xp_reward', 'active'];
    protected $casts = ['category' => \App\Enums\Gamification\AchievementCategory::class, 'active' => 'boolean'];
}