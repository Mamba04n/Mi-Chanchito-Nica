<?php
namespace App\Models\Gamification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class XpTransaction extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'amount', 'reason', 'reference_type', 'reference_id', 'metadata'];
    protected $casts = ['reason' => \App\Enums\Gamification\XpReason::class, 'metadata' => 'array'];
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}