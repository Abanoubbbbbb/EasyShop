<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /*
    |---------------------------------
    | SaaS SAFE QUERY FILTER
    |---------------------------------
    */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('company_id', Filament::auth()->user()->company_id);
    }

    /*
    |---------------------------------
    | FORM
    |---------------------------------
    */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            // 🟢 Name
            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(
                    fn($state, callable $set) =>
                    $set('slug', Str::slug($state))
                ),

            // 🟢 Slug (CLEAN SaaS UNIQUE)
            TextInput::make('slug')
                ->required()
                ->disabled()
                ->dehydrated()
                ->unique(
                    table: 'categories',
                    column: 'slug',
                    ignoreRecord: true,
                    modifyRuleUsing: function ($rule) {

                        $user = Filament::auth()->user();

                        abort_if(! $user?->company_id, 403);

                        return $rule->where('company_id', $user->company_id);
                    }
                ),
        ]);
    }

    /*
    |---------------------------------
    | TABLE
    |---------------------------------
    */
    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    /*
    |---------------------------------
    | RELATIONS
    |---------------------------------
    */
    public static function getRelations(): array
    {
        return [];
    }

    /*
    |---------------------------------
    | PAGES
    |---------------------------------
    */
    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
