<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',

        'cost_price',
    ];

    /*
    |---------------------------------
    | RELATIONS
    |---------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /*
    |---------------------------------
    | PROFIT CALCULATION
    |---------------------------------
    */

    public function getProfitAttribute()
    {
        return ($this->sale_price - $this->cost_price) * $this->quantity;
    }

    /*
    |---------------------------------
    | GLOBAL SCOPE (SaaS 🔥)
    |---------------------------------
    */

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('company', function (Builder $builder) {
    //         if (Filament::auth()->check()) {
    //             $builder->where('company_id', Filament::auth()->user()->company_id);
    //         }
    //     });
    // }
}
