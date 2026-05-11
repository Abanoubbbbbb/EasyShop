<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * الشركة اللي المستخدم تابع ليها
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope مهم لعزل بيانات كل شركة (SaaS isolation)
     */
    public function scopeForCompany($query, $companyId = null)
    {
        return $query->where('company_id', $companyId ?? auth()->user()?->company_id);
    }

    /**
     * Helper: هل المستخدم Owner؟
     */
    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    /**
     * Helper: هل Admin؟
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Helper: هل Employee؟
     */
    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }
}
