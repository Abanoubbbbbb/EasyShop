<?php

namespace App\Filament\Resources\Employees;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use BackedEnum;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Employees';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    // 🔐 Multi-tenancy (Company isolation)
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->when(
                $user,
                fn($query) =>
                $query->where('company_id', $user->company_id)
            );
    }

    // 🧾 FORM
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn($context) => $context === 'create')
                ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn($state) => filled($state)),

            Forms\Components\Select::make('role')
                ->label('Role')
                ->options(
                    \Spatie\Permission\Models\Role::whereIn('name', [
                        'admin',
                        'employee',
                    ])->pluck('name', 'name')
                )
                ->required(),
        ]);
    }

    // 📊 TABLE
    public static function table(Table $table): Table
    {
        return $table->columns([

            Tables\Columns\TextColumn::make('name')
                ->searchable(),

            Tables\Columns\TextColumn::make('email')
                ->searchable(),

            Tables\Columns\TextColumn::make('roles.name')
                ->label('Role')
                ->badge(),
        ]);
    }

    // 🔐 FIXED VISIBILITY (important)
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user || ! $user->company_id) {
            return false;
        }

        return $user->hasAnyRole(['owner', 'admin', 'employee']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user?->hasAnyRole(['owner', 'admin']) ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        return $user?->hasAnyRole(['owner', 'admin']) ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();

        return $user?->hasRole('owner') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Employees\Pages\ListEmployees::route('/'),
            'create' => \App\Filament\Resources\Employees\Pages\CreateEmployee::route('/create'),
            'edit' => \App\Filament\Resources\Employees\Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
