<?php

namespace App\Models;

use App\Enums\FinancialMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'financial_account_id',
        'type',
        'amount',
        'currency',
        'reference_type',
        'reference_id',
        'description',
        'notes',
        'occurred_at',
        'created_by',
        'previous_balance',
        'new_balance',
    ];

    protected $casts = [
        'type' => FinancialMovementType::class,
        'occurred_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
