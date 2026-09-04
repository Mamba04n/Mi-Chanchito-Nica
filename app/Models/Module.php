<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'active',
        'dependencies',
    ];

    protected $casts = [
        'active' => 'boolean',
        'dependencies' => 'array',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_modules')
            ->withPivot('enabled_at', 'disabled_at', 'settings')
            ->withTimestamps();
    }
}
