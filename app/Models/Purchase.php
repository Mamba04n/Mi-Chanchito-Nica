<?php

namespace App\Models;

use App\Enums\PurchaseStatus;
use App\Enums\PurchaseType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'number',
        'supplier_reference',
        'purchase_date',
        'due_date',
        'currency',
        'purchase_type',
        'status',
        'warehouse_id',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'paid_amount',
        'balance',
        'notes',
        'confirmed_at',
        'cancelled_at',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'due_date' => 'date',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'purchase_type' => PurchaseType::class,
        'status' => PurchaseStatus::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payable(): HasOne
    {
        return $this->hasOne(AccountPayable::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
