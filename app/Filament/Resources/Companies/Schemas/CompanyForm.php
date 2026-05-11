<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                // 🏢 اسم الشركة
                TextInput::make('name')
                    ->label('اسم الشركة')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                // 🖼️ اللوجو
                FileUpload::make('logo')
                    ->label('شعار الشركة')
                    ->directory('logos')
                    ->image()
                    ->nullable(),

                // 📦 نوع الاشتراك
                Select::make('plan')
                    ->label('نوع الاشتراك')
                    ->options([
                        'free' => 'Free',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->required()
                    ->default('free'),

                // 💰 السعر
                TextInput::make('subscription_price')
                    ->label('سعر الاشتراك')
                    ->numeric()
                    ->default(0),

                // 📅 تاريخ الانتهاء
                DatePicker::make('subscription_ends_at')
                    ->label('تاريخ انتهاء الاشتراك')
                    ->nullable(),

                // 🔔 قبل كام يوم ينبه
                TextInput::make('notify_before_days')
                    ->label('التنبيه قبل (أيام)')
                    ->numeric()
                    ->default(3),

                // ✅ حالة الشركة
                Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),

            ]);
    }
}
