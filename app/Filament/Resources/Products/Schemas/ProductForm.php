<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Facades\Filament;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                // 🟢 Name
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                // 🟢 Slug (🔥 SaaS SAFE CLEAN)
                TextInput::make('slug')
                    ->required()
                    ->disabled() // 👈 مهم
                    ->dehydrated() // 👈 مهم
                    ->unique(
                        table: 'products',
                        column: 'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {

                            $user = Filament::auth()->user();

                            abort_if(! $user?->company_id, 403);

                            return $rule->where('company_id', $user->company_id);
                        }
                    ),

                // 🟢 Category
                Select::make('category_id')
                    ->options(function () {
                        $companyId = Filament::auth()->user()?->company_id;

                        if (! $companyId) {
                            return [];
                        }

                        return \App\Models\Category::where('company_id', $companyId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                // 🔥 Cost Price
                TextInput::make('cost_price')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                // 🔥 Sale Price
                TextInput::make('sale_price')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                // 🟢 Discount
                TextInput::make('discount')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100),

                // 🟢 Stock
                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                // 🟢 Description
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                // 🟢 Image
                FileUpload::make('image')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->imagePreviewHeight('70')
                    ->required(),
            ]);
    }
}
