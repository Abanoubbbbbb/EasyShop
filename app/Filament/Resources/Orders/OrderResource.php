<?php

namespace App\Filament\Resources\Orders;

use BackedEnum;

use App\Models\Order;

use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\CreateOrder;

use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    protected static string | BackedEnum | null $navigationIcon =
    'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'الطلبات';

    protected static ?string $modelLabel = 'طلب';

    protected static ?string $pluralModelLabel = 'الطلبات';

    protected static ?int $navigationSort = 2;

    /*
    |--------------------------------------------------------------------------
    | Multi Tenant
    |--------------------------------------------------------------------------
    */

    protected static bool $isScopedToTenant = true;

    /**
     * فلترة البيانات حسب الشركة الحالية
     */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(filament()->getTenant(), function ($query, $tenant) {
                $query->where('company_id', $tenant->id);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(OrderForm::getSchema());
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation Badge
    |--------------------------------------------------------------------------
    */

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()
            ->when(filament()->getTenant(), function ($query, $tenant) {
                $query->where('company_id', $tenant->id);
            })
            ->where('status', 'pending')
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
