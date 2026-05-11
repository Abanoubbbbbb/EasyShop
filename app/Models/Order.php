<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'total_price',
        'customer_name',
        'phone',
        'address',
        'status',
    ];

    /*
    |---------------------------------
    | RELATIONS
    |---------------------------------
    */

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /*
    |---------------------------------
    | ACCESSOR (PROFIT)
    |---------------------------------
    */

    public function getProfitAttribute()
    {
        return $this->items->sum(function ($item) {
            return ($item->sale_price - $item->cost_price) * $item->quantity;
        });
    }

    /*
    |---------------------------------
    | GLOBAL SCOPE (IMPORTANT 🔥 SaaS)
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
