<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'educational_preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'educational_preferences' => 'array',
        ];
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('role_id', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function getRoleInCompany(Company $company): ?Role
    {
        $pivot = $this->companies()->where('companies.id', $company->id)->first()?->pivot;
        if (!$pivot || !$pivot->role_id) return null;
        
        return Role::find($pivot->role_id);
    }

    public function hasPermission(string $permissionKey, Company $company): bool
    {
        $role = $this->getRoleInCompany($company);
        if (!$role) return false;

        if ($role->key === 'owner') return true; // Owner has all permissions

        return $role->permissions()->where('key', $permissionKey)->exists();
    }
}
