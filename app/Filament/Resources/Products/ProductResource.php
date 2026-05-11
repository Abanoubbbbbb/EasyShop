<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /*
    |---------------------------------
    | SaaS Multi-Tenant Filter (FIXED)
    |---------------------------------
    */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(Filament::auth()->user()?->company_id, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            });
    }

    /*
    |---------------------------------
    | Form
    |---------------------------------
    */
    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    /*
    |---------------------------------
    | Table
    |---------------------------------
    */
    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    /*
    |---------------------------------
    | Pages
    |---------------------------------
    */
    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
