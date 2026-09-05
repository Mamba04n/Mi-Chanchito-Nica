<?php

namespace App\Models;

use App\Enums\FinancialAccountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'currency',
        'opening_balance',
        'current_balance',
        'description',
        'active',
        'is_default',
    ];

    protected $casts = [
        'type' => FinancialAccountType::class,
        'active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(FinancialMovement::class);
    }
}
