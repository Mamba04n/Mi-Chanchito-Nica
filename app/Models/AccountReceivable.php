<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\ReceivableStatus;

class AccountReceivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'invoice_id',
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
        'status' => ReceivableStatus::class,
        'original_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ReceivablePayment::class);
    }
}
