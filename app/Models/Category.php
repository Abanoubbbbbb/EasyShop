<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'company_id',
    ];

    /*
    |---------------------------------
    | Relationships
    |---------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |---------------------------------
    | AUTO SLUG (SAFE SaaS)
    |---------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {

            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }

            // 🔥 مهم: بدون Global Scope
            $slugExists = self::withoutGlobalScope('company')
                ->where('company_id', $category->company_id)
                ->where('slug', $category->slug)
                ->exists();

            if ($slugExists) {
                $category->slug .= '-' . uniqid();
            }
        });

        static::updating(function ($category) {

            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);

                // 🔥 مهم: بدون Global Scope
                $slugExists = self::withoutGlobalScope('company')
                    ->where('company_id', $category->company_id)
                    ->where('slug', $category->slug)
                    ->exists();

                if ($slugExists) {
                    $category->slug .= '-' . uniqid();
                }
            }
        });
    }

    /*
    |---------------------------------
    | GLOBAL SCOPE (SaaS CORE 🔥)
    |---------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {

            $user = Filament::auth()->user();

            if ($user?->company_id) {
                $builder->where('company_id', $user->company_id);
            }
        });
    }
}
