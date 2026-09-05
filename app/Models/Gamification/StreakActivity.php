<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class StreakActivity extends Model {
    use HasFactory;
    const UPDATED_AT = null;
    protected $fillable = ['user_id', 'activity_date', 'source_type', 'source_id'];
    protected $casts = ['activity_date' => 'date'];
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}