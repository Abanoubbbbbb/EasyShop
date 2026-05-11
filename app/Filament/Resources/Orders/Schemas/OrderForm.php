<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;

class OrderForm
{
    public static function getSchema(): array
    {
        return [

            /*
            |--------------------------
            | معلومات الطلب
            |--------------------------
            */

            TextInput::make('customer_name')
                ->label('اسم العميل')
                ->disabled(),

            TextInput::make('phone')
                ->label('الهاتف')
                ->disabled(),

            Select::make('status')
                ->label('الحالة')
                ->options([
                    'pending' => 'قيد الانتظار',
                    'processing' => 'جاري التجهيز',
                    'completed' => 'تم التسليم',
                    'canceled' => 'ملغي',
                ])
                ->required(),

            TextInput::make('address')
                ->label('العنوان')
                ->disabled(),

            /*
            |--------------------------
            | المنتجات
            |--------------------------
            */

            Repeater::make('items')
                ->relationship('items')
                ->schema([

                    Select::make('product_id')
                        ->label('المنتج')
                        ->relationship('product', 'name')
                        ->disabled()
                        ->searchable(),

                    TextInput::make('quantity')
                        ->label('الكمية')
                        ->disabled(),

                    TextInput::make('sale_price')
                        ->label('السعر')
                        ->suffix('EGP')
                        ->disabled(),

                ]),

            /*
            |--------------------------
            | الملخص
            |--------------------------
            */

            Placeholder::make('created_at')
                ->label('تاريخ الطلب')
                ->content(fn($record) => $record?->created_at?->format('Y-m-d H:i')),

            TextInput::make('total_price')
                ->label('الإجمالي')
                ->disabled()
                ->suffix('EGP'),

        ];
    }
}
