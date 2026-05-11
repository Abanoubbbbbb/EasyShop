<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'description',
        'subscription_ends_at',
        'subscription_price',
        'is_active',


    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            $company->slug = Str::slug($company->name . '-' . uniqid());
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function getIsExpiredAttribute()
    {
        return $this->subscription_ends_at
            && now()->gt($this->subscription_ends_at);
    }
    public function getDaysLeftAttribute()
    {
        if (!$this->subscription_ends_at) return null;

        return now()->diffInDays($this->subscription_ends_at, false);
    }
}
