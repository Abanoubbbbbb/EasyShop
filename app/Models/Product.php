<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'price',
    ];

    // العلاقة بالشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Global Scope لتقييد المنتجات حسب الشركة
    protected static function booted(): void
    {
        static::addGlobalScope('company', function (Builder $query) {
            // تأكد من وجود User مرتبط بشركة قبل إضافة الشرط
            $user = Auth::user();
            if ($user && $user->company_id) {
                $query->where('company_id', $user->company_id);
            }
        });
    }
}
