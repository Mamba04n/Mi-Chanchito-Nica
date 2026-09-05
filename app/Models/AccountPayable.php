<?php

namespace App\Models;

use App\Enums\PayableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountPayable extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'purchase_id',
        'original_amount',
        'paid_amount',
        'balance',
        'issued_at',
        'due_date',
        'status',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'due_date' => 'date',
        'status' => PayableStatus::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PayablePayment::class);
    }
}
