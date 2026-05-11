<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;

class TopProducts extends TableWidget
{
    protected static ?string $heading = 'Top Selling Products';

    // 🔥 تحديد المفتاح الفريد لكل سجل في الجدول
    public function getTableRecordKey($record): string
    {
        return (string) $record->product_id;
    }

    public function table(Table $table): Table
    {
        $companyId = auth()->user()->company_id;  // تحديد الـ company_id للمستخدم الحالي

        return $table
            ->query(
                OrderItem::query()
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->where('orders.company_id', $companyId)  // فلترة حسب company_id
                    ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(quantity * sale_price) as revenue')
                    ->with('product')  // جلب بيانات المنتج المرتبطة
                    ->groupBy('product_id')
                    ->orderByDesc('total_qty')  // ترتيب حسب أعلى كمية مباعة
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product'),

                TextColumn::make('total_qty')
                    ->label('Sold Qty'),

                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('EGP'),  // عرض الإيرادات بالـ EGP
            ]);
    }
}
