<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
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
        'payment_terms_days',
        'notes',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'payment_terms_days' => 'integer',
    ];
}
