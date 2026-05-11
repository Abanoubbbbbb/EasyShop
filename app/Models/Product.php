<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Facades\Filament;

class Product extends Model
{
    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'description',
        'cost_price',
        'sale_price',
        'quantity',
        'image',
        'discount',
        'slug',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /*
    |---------------------------------
    | AUTO SLUG
    |---------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /*
    |---------------------------------
    | GLOBAL SCOPE (IMPORTANT 🔥)
    |---------------------------------
    */

    protected static function booted(): void
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (Filament::auth()->check()) {
                $builder->where('company_id', Filament::auth()->user()->company_id);
            }
        });
    }
}
