<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            // 👤 Name
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            // 📧 Email
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            // 🔑 Password
            TextInput::make('password')
                ->password()
                ->required(fn($context) => $context === 'create')
                ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn($state) => filled($state)),

            // 🎭 Role (UI فقط)
            Select::make('role')
                ->label('Role')
                ->options(function () {

                    $user = Auth::user();

                    if ($user?->hasRole('owner')) {
                        return Role::pluck('name', 'name');
                    }

                    return Role::whereIn('name', ['admin', 'employee'])
                        ->pluck('name', 'name');
                })
                ->required(),
        ]);
    }
}
