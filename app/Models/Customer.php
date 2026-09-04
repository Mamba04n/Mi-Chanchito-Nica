<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'legal_name',
        'identification',
        'email',
        'phone',
        'address',
        'credit_limit',
        'credit_days',
        'notes',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'credit_limit' => 'decimal:2',
        'credit_days' => 'integer',
    ];
}
